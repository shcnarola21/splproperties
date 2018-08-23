<?php
$action = site_url('register');
?>
<style>
    .login-cover {
        background-size: auto !important;
    }
</style>
<script type="text/javascript" src="assets/js/plugins/forms/inputs/formatter.min.js"></script>
<div class="registration_container">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">                
                <h2 class="page_header"><a href="<?php echo base_url();?>" ><img src="<?php echo base_url();?>assets/frontend/images/logo.jpg" alt="site logo"></a></h2>
            </div>
        </div>
        <div class="registration_form">
            <div class="login_back">
                <a href="<?php echo base_url(); ?>login" class="back_login_url"><i class="fas fa-long-arrow-alt-left"></i> 
                    Back to Login
                </a>
            </div>
            <h5 class="form_title">
                Fill in the form below to register in system now!
            </h5>
            <form method="post" action="<?php echo $action; ?>" id="User_Register_Form">
                <?php $this->load->view('alert_view'); ?>
                <div id="payment-errors"></div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group custom_form_container">
                            <span><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control ark_form" placeholder="First name" name="first_name" id="first_name">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group custom_form_container">
                            <span><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control ark_form" placeholder="Last name" name="last_name" id="last_name">
                        </div>
                    </div>
                </div>
                <div class="row">                  
                    <div class="col-sm-6">
                        <div class="form-group custom_form_container">
                            <span><i class="fas fa-envelope"></i></span>
                            <input type="text" class="form-control ark_form" placeholder="Email" name="email_id" id="email_id">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group custom_form_container">
                            <span><i class="fas fa-user"></i></span>
                            <input type="password" class="form-control ark_form" placeholder="Password" name="password" id="password">
                        </div>
                    </div>                    
                </div>               
                <div class="row">                    
                    <div class="col-sm-6">
                        <div class="form-group custom_form_container">
                            <span><i class="fas fa-phone"></i></span>
                            <input type="text" class="form-control ark_form format-phone-number" placeholder="Contact Number" name="contact_number" id="contact_number">
                        </div>
                    </div>
                </div>               
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group custom_form_container">
                            <span><i class="fas fa-building"></i></i></span>
                            <textarea class="form-control ark_form" rows="3" placeholder="Address" name="address" id="address"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group custom_form_container">
                            <span><i class="fas fa-building"></i></span>
                            <input type="text" class="form-control ark_form" id="city" Placeholder="City"  name="city" id="city"/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group custom_form_container">
                            <span><i class="fas fa-building"></i></span>
                            <input type="text" class="form-control ark_form" id="city" Placeholder="State"  name="state" id="state"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group custom_form_container">
                            <span><i class="fas fa-building"></i></span>
                            <input type="text" class="form-control ark_form" name="country" id="country" Placeholder="Country">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group custom_form_container">
                            <span><i class="fas fa-building"></i></span>
                            <input type="text" class="form-control ark_form" name="zip_code" id="zip_code" Placeholder="Zip Code">
                        </div>
                    </div>
                </div>                  
                <button type="submit" class="btn btn-default form_submit_btn btn_login">Register</button>
            </form>
        </div>
    </div>
</div>
<script>
    var site_url = '<?php echo site_url(); ?>'
    var remoteURL = site_url + "home/checkUnique_Email";
    var codeURL = site_url + "home/checkUniquePromotionCode";   
</script>
<script type="text/javascript" src="assets/js/custom_pages/front/regi.js"></script>