$(document).ready(function () {

    $(document).find(".ra_styled").uniform({
        radioClass: 'choice'
    });

    $("#frm_user_edit").validate({
        ignore: 'input[type=hidden]', // ignore hidden fields
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
            $(element).remove();
        },
        rules: {
            name: {required: true},
            email_id: {
                required: true,
                email: true,
                remote: remoteURL
            },
            confirm_password: {
                minlength: 5,
            },
            confirm_password: {
                minlength: 5,
                equalTo: "#password"
            }
        },
        messages: {
            email_id: {remote: $.validator.format("This Email already exist!")},
        },
        submitHandler: function (form) {
            jQuery('#frm_user_edit .spinner').removeClass('hide');
            $(document).find('.frm-action button').attr('disabled', 'disabled');
            $(".userMessageDiv").html("");
            var data = jQuery("#frm_user_edit").serialize();
            jQuery.ajax({
                type: "POST",
                url: base_url + "users/save",
                data: data,
                success: function (data) {
                    jQuery('#frm_user_edit .spinner').addClass('hide');
                    var result = data.split("^");
                    $("html, body").animate({scrollTop: 0}, "slow");
                    if (result[0] == "0") {
                        $(".userMessageDiv").html("");
                        $(".userMessageDiv").html(result[1]);
                        $(".userMessageDiv").show();
                    } else {
                        var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>User saved successfully.</div>';
                        $("#add_device_frm input").val('');
                        $(".userMessageDiv").html(msg);
                        $(".userMessageDiv").show();                       
                        setTimeout(function () {
                            $(".userMessageDiv").hide();
                            if(result[1] == '1'){
                                 window.location.href = base_url + 'users';
                            }
                        }, 2000);
                    }
                }
            });

        },
        invalidHandler: function () {
            $('.btn_login').prop('disabled', false);
        }
    });

    $("#frm_user_add").validate({
        ignore: 'input[type=hidden]', // ignore hidden fields
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
            $(element).remove();
        },
        rules: {
            name: {required: true},
            email_id: {
                required: true,
                email: true,
                remote: remoteURL
            },
            confirm_password: {
                minlength: 5,
            },
            confirm_password: {
                minlength: 5,
                equalTo: "#password"
            }
        },
        messages: {
            email_id: {remote: $.validator.format("This Email already exist!")},
        },
        submitHandler: function (form) {
            jQuery('#frm_user_add .spinner').removeClass('hide');
            $(document).find('.frm-action button').attr('disabled', 'disabled');
            $(".userMessageDiv").html("");
            var data = jQuery("#frm_user_add").serialize();
            jQuery.ajax({
                type: "POST",
                url: base_url + "users/add",
                data: data,
                success: function (data) {
                    jQuery('#frm_user_add .spinner').addClass('hide');
                    var result = data.split("^");
                    $("html, body").animate({scrollTop: 0}, "slow");
                    if (result[0] == "0") {
                        $(".userMessageDiv").html("");
                        $(".userMessageDiv").html(result[1]);
                        $(".userMessageDiv").show();
                    } else {
                        var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>User saved successfully.</div>';
                        $("#add_device_frm input").val('');
                        $(".userMessageDiv").html(msg);
                        $(".userMessageDiv").show();                       
                        setTimeout(function () {
                            $(".userMessageDiv").hide();
                            if(result[1] == '1'){
                                 window.location.href = base_url + 'users';
                            }
                        }, 2000);
                    }
                }
            });

        },
        invalidHandler: function () {
            $('.btn_login').prop('disabled', false);
        }
    });
});