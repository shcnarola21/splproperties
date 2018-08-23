$(document).ready(function () {
    var primary = document.querySelector('.switchery-primary');
    var switchery = new Switchery(primary, {color: '#2196F3'});
});

jQuery(document).on('click', "#payment_system", function (event) {
    if ($(this).is(':checked')) {
        $(document).find('.payment_div').show(200);
    } else {
        $(document).find('.payment_div').hide(200);
    }
});

jQuery(document).on('submit', "#payment_system_frm", function (event) {
    save_payment_system_settings('payment_system_frm');
    event.preventDefault();
});

function save_payment_system_settings(formid) {
    var data = jQuery("#" + formid).serialize();
    $(".userMessageDiv").hide();
    jQuery('#' + formid + ' .spinner').removeClass('hide');
    $(document).find('.frm-action button').attr('disabled', 'disabled');
    $.post(base_url + "payment_system/save", data,
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
                        msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button class="close" data-dismiss="alert"></button>Payment system settings saved successfully.</div>';
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