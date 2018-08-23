<form action="" method="post" id="reset_password_form">
    <div class="forget_password_page reset_page login_bg">
        <div>
            <div>
                <div>
                    <div class="login_container">
                        <div class="login_left_panel">
                           <h3><a href="<?php echo base_url();?>"><img src="<?php echo base_url(); ?>assets/frontend/images/logo.jpg" alt="site logo"></a></h3>
                            <p>Share pictures with family and friends quick and easy outside social media. </p>
                            <a><i class="fas fa-envelope"></i>contact@arksecurity.com</a>
                        </div>
                        <div class="login_right_panel">
                            <div class="reset_header">
                                <h2><span>R</span>eset <span>P</span>assword</h2>
                                <h3>Plaese enter your new password to Reset your password</h3>
                            </div>
                            <?php $this->load->view('alert_view'); ?>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group custom_form_container">
                                        <input type="password" class="form-control ark_form" name="txt_password" id="txt_password" placeholder="Password">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group custom_form_container">
                                        <input type="password" class="form-control ark_form" name="txt_c_password" id="txt_c_password" placeholder="Confirm Password">
                                    </div>
                                </div>
                            </div>
                            <div class="login_footer">
                                <div style="float:left">
                                    <a href="<?php echo site_url('login'); ?>" class="back_login_url"><i class="fas fa-long-arrow-alt-left"></i> Back to Login</a>
                                </div>
                                <button class="Login_btn reset_btn btn_reset_pwd">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    var validator = $("#reset_password_form").validate({
    ignore: 'input[type=hidden], .select2-search__field, #txt_status', // ignore hidden fields
            errorClass: 'validation-error-label',
            successClass: 'validation-valid-label',
            highlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
            },
            unhighlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
            },
            errorPlacement: function (error, element) {
            $(element).parent().find('.form_success_vert_icon').remove();
            if (element.parents('div').hasClass("checker") || element.parents('div').hasClass("choice") || element.parent().hasClass('bootstrap-switch-container')) {
            if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
            error.appendTo(element.parent().parent().parent().parent());
            } else {
            error.appendTo(element.parent().parent().parent().parent().parent());
            }
            } else if (element.parents('div').hasClass('checkbox') || element.parents('div').hasClass('radio')) {
            error.appendTo(element.parent().parent().parent());
            } else if (element.parents('div').hasClass('has-feedback') || element.hasClass('select2-hidden-accessible')) {
            error.appendTo(element.parent());
            } else if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
            error.appendTo(element.parent().parent());
            } else if (element.parent().hasClass('uploader') || element.parents().hasClass('input-group')) {
            error.appendTo(element.parent().parent());
            } else {
            error.insertAfter(element);
            }
            },
            validClass: "validation-valid-label",
            success: function (element) {
            $(element).parent().find('.form_success_vert_icon').remove();
            $(element).parent().append('<div class="form_success_vert_icon form-control-feedback"><i class="icon-checkmark-circle"></i></div>');
            $(element).remove();
            },
            rules: {
            txt_password: { required: true, minlength: 8 }
            txt_c_password: { required: true, equalTo: '#txt_password' }
            },
            messages: {
            txt_password: {
            required: 'Please enter password!',
                    minlength: 'Password must be atleast 8 characters long!'
            },
                    txt_c_password: {
                    required: 'Please confirm password!',
                            equalTo: 'Password does not match!'
                    },
            },
            submitHandler: function(form){
            form.submit();
            $('.btn_reset_pwd').prop('disabled', true);
            },
            invalidHandler: function() {
            $('.btn_reset_pwd').prop('disabled', false);
            }
    });
</script>
