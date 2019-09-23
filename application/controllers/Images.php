<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Images extends CI_Controller {
        
    function __construct(){

        parent::__construct();
        $this->load->model('User_model', 'user_model', TRUE);
        $this->load->library('form_validation');    
        $this->load->helper('security');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->status = $this->config->item('status'); 
        $this->roles = $this->config->item('roles');

        if($this->isLoggedIn()){
            $this->loginData = $this->session->userdata; 
        }

    }     

    private function isLoggedIn(){
        return !empty($this->session->userdata['email']);
    }

    public function upload(){

        $response = [];

        if(isset($_FILES['picture'])){

            $format = preg_replace("#^image\/#","",$_FILES['picture']['type']);
            if(preg_match('#jp(e)?g|png|gif#',$format)){

                //prepare for upload

                $fileContents = addslashes(file_get_contents($_FILES['picture']['tmp_name']));
                $picSubmission = $this->db->simple_query("INSERT INTO pictures(format,blobb) VALUES('$format','$fileContents')");
                if($picSubmission){
                    $picQ = $this->db->query("SELECT picture_id FROM pictures ORDER BY picture_id DESC");
                    $pic = $picQ->row_array()['picture_id'];
                    $response['data'] = ['picture' => $pic];
                    $response['success'] = true;
                }
            }
        }
        echo json_encode($response);

    }

    public function index($id){

        if($id !== NULL){

            $pictur = $this->db->query("SELECT * FROM pictures WHERE picture_id='".$this->security->xss_clean($id)."'");

            if(count($pictur->result()) > 0){

                $actualData = $pictur->row_array();     
                header("Content-type: ".$actualData['format']);
                header('Content-Disposition: inline;');

                echo $actualData['blobb'];

            }
            else{
                redirect(site_url());
            }

        }

    }        

}

?>