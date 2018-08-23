$(document).ready(function () {
    $("#User_Register_Form").validate({
        ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
        errorClass: 'validation-error-label',
        successClass: 'validation-valid-label',
        highlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },
        unhighlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },
        errorPlacement: function (error, element) {
            $(element).parent().find('.form_success_icon').remove();
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
            $(element).parent().find('.form_success_icon').remove();
            $(element).parent().append('<div class="form_success_icon form-control-feedback" style="right:0;left:auto"><i class="icon-checkmark-circle"></i></div>');
            $(element).remove();
        },
        rules: {
            first_name: {required: true, maxlength: 100},
            last_name: {required: true, maxlength: 100},
            email_id: {
                required: true,
                email: true,
                remote: remoteURL
                        // accept: "[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,5}"
            },
            password: {required: true},
            contact_number: {required: true},
            address: {required: true},
            city: {required: true},
            state: {required: true},
            country: {required: true},
            zip_code: {required: true}
        },
        messages: {
            email_id: {remote: $.validator.format("This Email already exist!")},
        },
        submitHandler: function (form) {
            form.submit();
            //$('.btn_login').prop('disabled', true);
        },
        invalidHandler: function () {
            $('.btn_login').prop('disabled', false);
        }
    });
})
