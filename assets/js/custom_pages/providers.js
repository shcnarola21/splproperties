$(document).ready(function () {

    $(document).find(".ra_styled").uniform({
        radioClass: 'choice'
    });

    jQuery(document).on('submit', "#frm_provider_add", function (event) {
        save_provider('frm_provider_add');
        event.preventDefault();
    });

    jQuery(document).on('submit', "#frm_provider_edit", function (event) {
        save_provider('frm_provider_edit');
        event.preventDefault();
    });

    function save_provider(formid) {
        jQuery('#' + formid + ' .spinner').removeClass('hide');
        $(document).find('.form-wrapper .icon-info22').remove();
        $(document).find('.frm-action button').attr('disabled', 'disabled');
        $(".userMessageDiv").html("");
        var data = jQuery("#" + formid).serialize();
        jQuery.ajax({
            type: "POST",
            url: base_url + "providers/save",
            data: data,
            success: function (data) {
                jQuery('#' + formid + ' .spinner').addClass('hide');
                $(document).find('.frm-action button').attr('disabled', false);
                var json = jQuery.parseJSON(data);
                if (!json.status) {
                    jQuery('#' + formid + ' #package_btn .spinner').addClass('hide');
                    high_light_tab(json.tabs);
                    $('#' + formid + ' .userMessageDiv').html("");
                    $('#' + formid + ' .userMessageDiv').html(json.msg);
                    $('#' + formid + ' .userMessageDiv').show();
                } else {
                    var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button class="close" data-dismiss="alert"><span>x</span></button>Provider details saved successfully.</div>';
                    $('#' + formid + ' .userMessageDiv').html(msg);
                    $('#' + formid + ' .userMessageDiv').show();
                    jQuery('#' + formid + ' #package_btn .spinner').addClass('hide');
                    package_services();
                    setTimeout(function () {
                        $('#' + formid + ' .userMessageDiv').hide();
                        window.location.href = base_url + 'providers';
                    }, 2000);
                }
            }
        });
    }

});