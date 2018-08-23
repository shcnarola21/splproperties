$(document).ready(function () {
    CKEDITOR.replace('message', {
        height: '400px',
        extraPlugins: 'forms'
    });

    tag_it();
});

jQuery(document).on('submit', "#messaging_email_frm", function (event) {
    send_messaging_email_frm('messaging_email_frm');
    event.preventDefault();
});

jQuery(document).on('change', ".op_to", function (event) {
    if ($(this).val() == 'all') {
        $(document).find('.div_email_to').hide(200);
    } else {
        $(document).find('.div_email_to').show(200);
    }
});

function send_messaging_email_frm(formid) {
    for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }
    var data = jQuery("#" + formid).serialize();
    $(".userMessageDiv").hide();
    jQuery('#' + formid + ' .spinner').removeClass('hide');
    $(document).find('.frm-action button').attr('disabled', 'disabled');
    $.post(base_url + "messaging/send", data,
            function (data) {
                $(document).find('.frm-action button').removeAttr('disabled');
                jQuery('#' + formid + ' .spinner').addClass('hide');
                var result = data.split("^");
                if (result[0] == "0") {
                    if (result[0] == "0") {
                        $(".userMessageDiv").html("");
                        $(".userMessageDiv").html(result[1]);
                        $(".userMessageDiv").show();
                    }
                } else {
                    var msg = "";
                    if (result[0] == "1") {
                        msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button class="close" data-dismiss="alert"></button>Messages sent successfully.</div>';
                        $(".userMessageDiv").html(msg);
                        $(".userMessageDiv").show();

                    }
                    setTimeout(function () {
                        $(".userMessageDiv").hide();
                        location.reload();
                    }, 4000);
                }

            });
}

jQuery(document).on('submit', "#send_test_email_frm", function (event) {
    send_test_email('send_test_email_frm');
    event.preventDefault();
});


function send_test_email(formid) {
    var data = jQuery("#" + formid).serialize();
    $(".emailMessageDiv").hide();
    jQuery('#' + formid + ' .spinner').removeClass('hide');
    $(document).find('.frm-actions button').attr('disabled', 'disabled');
    $.post(base_url + "send_email/send_test_email", data,
            function (data) {
                $(document).find('.frm-actions button').removeAttr('disabled');
                jQuery('#' + formid + ' .spinner').addClass('hide');
                var result = data.split("^");
                if (result[0] == "0") {
                    if (result[0] == "0") {
                        $(".emailMessageDiv").html("");
                        $(".emailMessageDiv").html(result[1]);
                        $(".emailMessageDiv").show();
                    }
                } else {
                    var msg = "";
                    if (result[0] == "1") {
                        msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button class="close" data-dismiss="alert"></button>Test Email sent successfully.</div>';
                        $(".emailMessageDiv").html(msg);
                        $(".emailMessageDiv").show();
                    }
                    setTimeout(function () {
                        $(".emailMessageDiv").hide();
                        location.reload();
                    }, 4000);
                }

            });
}
