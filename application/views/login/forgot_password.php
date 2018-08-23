<form action="" method="post" id="forgot_password_form">
    <div class="forget_password_page login_bg">
        <div class="login_container">
            <div class="login_left_panel">
                <h3><a href="<?php echo base_url();?>"><img src="<?php echo base_url(); ?>assets/frontend/images/logo.jpg" alt="site logo"></a></h3>
                <p>Share pictures with family and friends quick and easy outside social media. </p>
                <a><i class="fas fa-envelope"></i>contact@ls3digital.com</a>
            </div>
            <div class="login_right_panel">
                <div class="reset_header">
                    <h2><span>F</span>orget <span>P</span>assword</h2>
                    <h3>Please enter the e-mail address and you will receive an email to reset your password to the email address you entered.</h3>
                </div>
                <?php $this->load->view('alert_view'); ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group custom_form_container">
                            <input type="text" class="form-control ark_form" name="txt_email" id="txt_email" placeholder="Email">
                        </div>
                    </div>
                </div>
                <div class="login_footer">
                    <div style="display:inline-block">
                        <a href="<?php echo site_url('login'); ?>" class="back_login_url"><i class="fas fa-long-arrow-alt-left"></i> Back to Login</a>
                    </div>
                    <button class="Login_btn reset_btn btn_forgot_pwd">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    var validator = $("#forgot_password_form").validate({
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
            txt_email: {required: true, email: true}
        },
        submitHandler: function (form) {
            form.submit();
            $('.btn_forgot_pwd').prop('disabled', true);
        },
        invalidHandler: function () {
            $('.btn_forgot_pwd').prop('disabled', false);
        }
    });
</script>
