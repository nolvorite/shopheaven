<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Main extends CI_Controller {
        
    public $status; 
    public $roles;
    public $rank = [
        'admin' => 3,
        'seller' => 2,
        'buyer' => 1
    ];

    private function isLoggedIn(){
        return !empty($this->session->userdata['email']);
    }

    function __construct(){

            parent::__construct();
            $this->load->helper('security');
            $this->load->model('User_model', 'user_model', TRUE);
            $this->load->library('form_validation');    
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $this->status = $this->config->item('status'); 
            $this->roles = $this->config->item('roles');

            if($this->isLoggedIn()){
                $this->loginData = $this->session->userdata; 
            }

        } 

    private function listOfProducts($productId = NULL){

        $clause = "";
        if($productId !== NULL){
            $clause = "WHERE p1.category_id= '" . intval($productId) . "'";
        }

        $list = $this->db->query("SELECT p1.product_id,p1.product_name,p1.quantity,p1.description,p1.price,c.category_name,p2.picture_id FROM products p1 LEFT JOIN pictures p2 ON p2.product_id = p1.product_id LEFT JOIN category c ON c.category_id = p1.category_id ".$clause);

        return $list->result_array();

    }

    private function listOfUsers($userId = NULL){
        $clause = "";
        if($userId !== NULL){
            $clause = "WHERE id= '" . intval($userId) . "'";
        }

        $list = $this->db->query("SELECT * FROM users ".$clause);

        $resultant = $userId !== NULL ? $list->row_array() : $list->result_array();

        return $resultant;
    }

    private function listOfCategories(){

        $list = $this->db->query("SELECT * FROM category");
        return $list->result_array();

    }

    public function manage_users(){
        //email,first_name,last_name,role
        $userData = $this->input->post();

        $query = $this->db->simple_query("UPDATE users SET email=".$this->db->escape($userData['email']).", first_name=".$this->db->escape($userData['first_name']).", last_name=".$this->db->escape($userData['last_name']).", role=".$this->db->escape($userData['role'])." WHERE id=". $this->db->escape($userData['id']));

        $this->session->set_flashdata('flash_message', $query ? 'User information edited successfully.' : 'Problem editing user information.');

        redirect(site_url()."main/admin/manage_users");

    }

    private function submitToDb($table,$array){
        /*
            format:
                for every key value pair, the key is a column and its value is the data to be inserted under said column.
                eg. [animal_type: cat, age: 6] would be INSERT INTO animals (animal_type,age) VALUES ('cat','6');
        */
        $query = "INSERT INTO $table (";
        $count = 0;
        foreach(array_keys($array) as $column){
            $count++;
            
            $query .= $column;
            if($count < count(array_keys($array))){
                $query .= ",";
            }
            
        }

        $query .= ")";
        $query .= " VALUES(";
        $count = 0;
        foreach($array as $value){
            $count++;
            $query .= $this->db->escape($value);
            if($count < count($array)){
                $query .= ",";
            }
        }

        $query .= ");";
        $submission = $this->db->simple_query($query);
        return $submission;

    }

     public function complete_order(){
        
        $orderData = $this->input->post();
        $orderData['user_id'] = intval($this->session->userdata['id']);
        $submitQuery = $this->submitToDb('orders',$orderData);
        $this->session->set_flashdata('flash_message', $submitQuery ? 'Order Was Successful. Expect your shipment within 3-5 days.' : 'Order was unsuccessful. Please try again. ');

        redirect(site_url()."main/orders");

        
    }

    public function admin($panel = NULL, $id = NULL){

        $this->load->view('header');       

        $rank = $this->rank[$this->loginData['role']];   

        if($this->isLoggedIn() && $rank >= 2){

            if($panel !== NULL){

                if($panel === "manage_users" && $rank < 3){
                    redirect(site_url()."main/admin");
                }

                switch($panel){
                    case "add_product":
                    case "add_category":
                    case "manage_users":
                    case "view_orders":
                        $data = ['panel' => $panel];

                        if($panel === "add_product" || $panel === "add_category"){
                            $data['products'] = $this->listOfProducts();
                            $data['categories'] = $this->listOfCategories();
                        }

                        if($panel === "manage_users"){
                            $data['solo'] = $id !== NULL;
                            $data['users'] = $this->listOfUsers($id);
                        }

                        if($panel === "view_orders"){
                            $data['orders'] = $this->getAllOrders();
                        }

                        $this->load->view("admin", $data);  
                    break;
                    default:
                        redirect(site_url()."main/admin");
                    break;
                }

            }else{
                                
                $this->load->view("admin");
                
            }

        }else{
            //redirect(site_url());
        }

                

        $this->load->view('footer');

    }

    public function products($category = NULL){

        $products = ($category === NULL) ? $this->listOfProducts() : $this->listOfProducts($category);

        if($this->isLoggedIn()){

            $this->load->view('header');  

            if($category !== NULL){

                if(count($products) < 1){
                    redirect(site_url()."main/products");
                }else{
                    $this->load->view('products',['products' => $products]);
                }
                
            }
            else{   
                $this->load->view('products',['products' => $products]);
            }
            $this->load->view('footer');
        }else{
            redirect(site_url());
        }

    }

    public function add_category(){
        $categoryData = $this->input->post();
        $submission = $this->submitToDb('category',$categoryData);
        $message = $submission ? "Adding new category was successful." : "Error adding new category. Please try again.";
        $this->session->set_flashdata('flash_message', $message);
        redirect(site_url()."main/admin/add_category");
    }

    public function add_product(){
        $productData = $this->input->post();
        $pictureId = $productData['picture_id'];
        unset($productData['picture_id']);
        $submission = $this->submitToDb('products',$productData);
        //get new product ID
        $productId = $this->db->query("SELECT * FROM products ORDER BY product_id DESC");
        $productId = $productId->row_array()['product_id'];
        $updatePic = $this->db->simple_query('UPDATE pictures SET product_id='.$productId.' WHERE picture_id = '.$pictureId);
        $checks = ($submission && $updatePic);
        $message = $checks ? "Adding new product was successful." : "Error adding new product. Please try again.";
        $this->session->set_flashdata('flash_message', $message);
        redirect(site_url()."main/admin/add_product");
    }

    private function getAllOrders(){
        $orders = $this->db->query("SELECT orders.order_id,orders.quantity,orders.user_id,orders.product_id,.orders.order_date,products.product_name,products.price FROM orders INNER JOIN products ON orders.product_id = products.product_id ORDER BY order_date DESC");
        return $orders->result_array();
    }

    public function orders($id = NULL){

        if($this->isLoggedIn()){

            $this->load->view('header');  

            $data = ['id' => $id];

            $orders = $this->db->query("SELECT orders.order_id,orders.quantity,orders.user_id,orders.product_id,.orders.order_date,products.product_name,products.price FROM orders INNER JOIN products ON orders.product_id = products.product_id WHERE user_id = " . intval($this->session->userdata['id']));
            $data['orders'] = $orders->result_array();

            if($id !== NULL){
                $product = $this->db->query("SELECT * FROM products LEFT JOIN pictures ON pictures.product_id = products.product_id WHERE products.product_id = ".$this->db->escape($id));
               
                if(count($product->result()) > 0){
                    $data['content'] = $product->row_array();
                }else{
                    redirect(site_url()."main/orders");
                }
            }

            $this->load->view('orders',$data);
          
            $this->load->view('footer');

            //$this->session->set_flashdata('flash_message', 'Order Was Successful. Expect your shipment within 3-5 days.');

        }else{
            redirect(site_url());
        }

    }

	public function index()
	{   

        $this->load->view('header');

        
        $data = $this->session->userdata; 

        $products = $this->listOfProducts();

        if($this->isLoggedIn()){

            $this->load->view('interface_logged',['products' => $products]);

        }else{

            $this->load->view('interface_guest',['products' => $products]);          

        }

        $this->load->view('footer');
    
	}

    public function login(){
        if(isset($_POST)){
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');    
            $this->form_validation->set_rules('password', 'Password', 'required'); 
        }

        $post = $this->input->post();  
        $clean = $this->security->xss_clean($post);
        
        $userInfo = $this->user_model->checkLogin($clean);
        
        if(!$userInfo){
            $this->session->set_flashdata('flash_message', 'The login was unsucessful');
        }                

        foreach($userInfo as $key=>$val){
            $this->session->set_userdata($key, $val);
        }

        redirect(site_url().'main/');
    }

    //guest views only
    
    public function register()
    {
         
        $this->form_validation->set_rules('firstname', 'First Name', 'required');
        $this->form_validation->set_rules('lastname', 'Last Name', 'required');    
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');    
                   
        if ($this->form_validation->run() == FALSE) {   
            $this->load->view('header');
            $this->load->view('register');
            $this->load->view('footer');
        }else{                
            if($this->user_model->isDuplicate($this->input->post('email'))){
                $this->session->set_flashdata('flash_message', 'User email already exists');
                redirect(site_url().'main/login');
            }else{
                
                $clean = $this->security->xss_clean($this->input->post(NULL, TRUE));
                $id = $this->user_model->insertUser($clean); 
                $token = $this->user_model->insertToken($id);                                        
                
                $qstring = $this->base64url_encode($token);                    
                $url = site_url() . 'main/complete/token/' . $qstring;
                           
                // $message = '';                     
                // $message .= '<strong>You have signed up with our website</strong><br>';
                // $message .= '<strong>Please click:</strong> ' . $link;        

                header("Location: " . $url);                  

                //echo $message; //send this in email
                exit;
                 
                
            };              
        }
    }
    
    
    protected function _islocal(){
        return strpos($_SERVER['HTTP_HOST'], 'local');
    }
    
    public function complete()
    {                                   
        $token = base64_decode($this->uri->segment(4));       
        $cleanToken = $this->security->xss_clean($token);
        
        $user_info = $this->user_model->isTokenValid($cleanToken); //either false or array();           
        
        if(!$user_info){
            $this->session->set_flashdata('flash_message', 'Token is invalid or expired');
            redirect(site_url().'main/login');
        }            
        $data = array(
            'firstName'=> $user_info->first_name, 
            'email'=>$user_info->email, 
            'user_id'=>$user_info->id, 
            'token'=>$this->base64url_encode($token)
        );
       
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');              
        
        if ($this->form_validation->run() == FALSE) {   
            $this->load->view('header');
            $this->load->view('complete', $data);
            $this->load->view('footer');
        }else{
            
            $this->load->library('password');                 
            $post = $this->input->post(NULL, TRUE);
            
            $cleanPost = $this->security->xss_clean($post);
            
            $hashed = $this->password->create_hash($cleanPost['password']);    
            $cleanPost['password'] = $hashed;
            unset($cleanPost['passconf']);
            $userInfo = $this->user_model->updateUserInfo($cleanPost);
            
            if(!$userInfo){
                $this->session->set_flashdata('flash_message', 'There was a problem updating your record');
                redirect(site_url().'main/login');
            }
            
            unset($userInfo->password);
            
            foreach($userInfo as $key=>$val){
                $this->session->set_userdata($key, $val);
            }
            redirect(site_url().'main/');
            
        }
    }

    
    public function logout()
    {
        $this->session->sess_destroy();
        redirect(site_url().'main/');
    }
    
    public function forgot()
    {
        
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email'); 
        
        if($this->form_validation->run() == FALSE) {
            $this->load->view('header');
            $this->load->view('forgot');
            $this->load->view('footer');
        }else{
            $email = $this->input->post('email');  
            $clean = $this->security->xss_clean($email);
            $userInfo = $this->user_model->getUserInfoByEmail($clean);
            
            if(!$userInfo){
                $this->session->set_flashdata('flash_message', 'We cant find your email address');
                redirect(site_url().'main/login');
            }   
            
            if($userInfo->status != $this->status[1]){ //if status is not approved
                $this->session->set_flashdata('flash_message', 'Your account is not in approved status');
                redirect(site_url().'main/login');
            }
            
            //build token 
			
            $token = $this->user_model->insertToken($userInfo->id);                        
            $qstring = $this->base64url_encode($token);                  
            $url = site_url() . 'main/reset_password/token/' . $qstring;
            $link = '<a href="' . $url . '">' . $url . '</a>'; 
            
            $message = '';                     
            $message .= '<strong>A password reset has been requested for this email account</strong><br>';
            $message .= '<strong>Please click:</strong> ' . $link;             

            echo $message; //send this through mail
            exit;
            
        }
        
    }
    
    public function reset_password()
    {
        $token = $this->base64url_decode($this->uri->segment(4));                  
        $cleanToken = $this->security->xss_clean($token);
        
        $user_info = $this->user_model->isTokenValid($cleanToken); //either false or array();               
        
        if(!$user_info){
            $this->session->set_flashdata('flash_message', 'Token is invalid or expired');
            redirect(site_url().'main/login');
        }            
        $data = array(
            'firstName'=> $user_info->first_name, 
            'email'=>$user_info->email, 
            //'user_id'=>$user_info->id, 
            'token'=>$this->base64url_encode($token)
        );
       
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');              
        
        if ($this->form_validation->run() == FALSE) {   
            $this->load->view('header');
            $this->load->view('reset_password', $data);
            $this->load->view('footer');
        }else{
                            
            $this->load->library('password');                 
            $post = $this->input->post(NULL, TRUE);                
            $cleanPost = $this->security->xss_clean($post);                
            $hashed = $this->password->create_hash($cleanPost['password']);                
            $cleanPost['password'] = $hashed;
            $cleanPost['user_id'] = $user_info->id;
            unset($cleanPost['passconf']);                
            if(!$this->user_model->updatePassword($cleanPost)){
                $this->session->set_flashdata('flash_message', 'There was a problem updating your password');
            }else{
                $this->session->set_flashdata('flash_message', 'Your password has been updated. You may now login');
            }
            redirect(site_url().'main/login');                
        }
    }
        
    public function base64url_encode($data) { 
      return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
    } 

    public function base64url_decode($data) { 
      return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
    }       
}
