<?php
if ($this->input->get('redirect')) {
    $action = site_url('/login') . '?redirect=' . $this->input->get('redirect');
} else {
    $action = site_url('/login');
}
?>
<div class="login_page login_bg">
    <div>
        <div>
            <div>
                <div class="login_container">
                    <div class="login_left_panel">
                        <h3><a href="<?php echo base_url();?>" ><img src="<?php echo base_url();?>assets/frontend/images/logo.jpg" style="height:100px;" alt="SPL Properties"></h3></a>
                        <h3><a href="<?php echo base_url();?>" ><img src="<?php echo base_url();?>assets/frontend/images/canaca_logo.png"  style="height:100px;" alt="Canaca-Com"></h3></a>
                        <h3><a href="<?php echo base_url();?>" ><img src="<?php echo base_url();?>assets/frontend/images/qr.png" style="height:100px;" alt="QR NETWORKS"></h3></a>
                        <p>Billing systems for Canaca-com, QR Networks, and SPL Properties. Unauthorized access is prohibited.</p>         
                        <a><i class="fas fa-envelope"></i>sandro@ls3solutions.ca</a>
                    </div>
                    <div class="login_right_panel">
                        <form method="post" action="<?php echo $action; ?>" id="User_Login_Form">
                            <div>
                                <h2><span></span>Login</h2>
                                <!-- <span><a class="account_msg" href="<?php //echo site_url('register'); ?>">Don't have an account?</a></span> -->
                            </div>
                            <?php $this->load->view('alert_view'); ?>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group custom_form_container">
                                        <input type="text" class="form-control ark_form" placeholder="Email" name="txt_username" id="txt_username">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group custom_form_container">
                                        <input type="password" class="form-control ark_form" placeholder="Password" name="txt_password" id="txt_password">
                                        <!-- <a href="<?php //echo site_url('forgot_password'); ?>" class="forget_msg">Forget Password?</a> -->
                                    </div>
                                </div>

                            </div>
                            <div class="checkbox">
                                <label class="custom_check">&nbsp;Remember Me
                                    <input type="checkbox" checked="checked">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="login_footer">
                                <button class="Login_btn btn_login">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
