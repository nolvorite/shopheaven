<!doctype html>

<html>     
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/main.css">
        <title>XeiShoppe</title>
    </head>
    <body>
    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    
    <div id="header">
        <div class="container">
            <div class="row">
                <div class="col-lg-6" id="logo"><a href="<?php echo site_url(); ?>"><img src="<?php echo site_url(); ?>public/images/logo.png" alt="Logo"></a></div>
            </div>
        </div>
    </div>
    <div id="nav">
        <div class="container">
            <div class="row">
                <?php if(empty($this->session->userdata['email'])){ ?>
                <div class="col-lg-6 col-sm-12" id="quick_login">
                    <h3>Log In</h3>
                    <?php $fattr = array('class' => 'form-signin');
                         echo form_open(site_url().'main/login', $fattr); ?>
                    <div class="form-group">
                      <?php echo form_input(array(
                          'name'=>'email', 
                          'id'=> 'email', 
                          'placeholder'=>'Email', 
                          'class'=>'form-control form-control-sm', 
                          'value'=> set_value('email'))); ?>
                      <?php echo form_error('email') ?>
                      <?php echo form_password(array(
                          'name'=>'password', 
                          'id'=> 'password', 
                          'placeholder'=>'Password', 
                          'class'=>'form-control form-control-sm', 
                          'value'=> set_value('password'))); ?>
                      <?php echo form_error('password') ?>
                      <?php echo form_submit(array('value'=>'Let me in!', 'class'=>'btn btn-sm btn-dark')); ?>
                      <a href="<?php echo site_url(); ?>main/register" class="btn btn-sm btn-success">Sign Up!</a>
                    </div>
                    
                    <?php echo form_close(); ?>
                </div>
                <?php } else { ?>
                <div class="col-lg-6 col-sm-12" id="logged_menu">
                    Hello, <?php echo $this->session->userdata['email']; ?>. <a href="<?php echo site_url(); ?>main/logout">Log Out</a>
                </div>
                <?php } ?>
                
            </div>
        </div>
    </div>
    <?php
        $arr = $this->session->flashdata(); 
        if(!empty($arr['flash_message'])){
            $html = "<div class='container'>";
            $html .= '<div class="alert alert-warning flash-message">';
            $html .= $arr['flash_message']; 
            $html .= '</div></div>';

            echo $html;
        }
    ?>
    <div id="content">
    <div class="container">
        <div class="row">

        
        
         
        