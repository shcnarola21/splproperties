jQuery(document).on('click', ".property_camera", function (e) {
    var property = $(this).data('property');
    $(".manage_camera_div").load(base_url + 'camera/manage_cameras_view', {property: property}, function () {
        jQuery('#modal_manage_camera').modal('show');

    });
    e.preventDefault();
});

jQuery(document).on('click', ".camera_url", function (e) {
    var stream_url = $(this).data('url');
    var camera = $(this).data('camera');
    $(".manage_camera_stream").load(base_url + 'camera/manage_cameras_stream', {camera: camera, stream_url: stream_url}, function () {
        jQuery('#modal_manage_camera_stream').modal('show');
    });
    e.preventDefault();
});
jQuery(document).on('click', ".gotovideo", function (e) {
    $(document).find('.gotoimage').removeClass('hide');
    $(document).find('.stream_video_url_div').removeClass('hide');
    $(document).find('.stream_image_url_div').addClass('hide');
    var live_stream_url = $("#live_video_url").val();
    window.jwplayer('preview_live_video_url_div').setup({
        "file": live_stream_url,
        "image": base_url + 'assets/images/live_stream_image.png',
        "autostart": true,
        "width": '100%'
    });
    e.preventDefault();
});
jQuery(document).on('click', ".gotoimage", function (e) {
    $(document).find('.gotoimage').addClass('hide');
    $(document).find('.stream_video_url_div').addClass('hide');
    $(document).find('.stream_image_url_div').removeClass('hide');
    e.preventDefault();
});

$(document).on('click', '.add_camera', function (e) {
    $(document).find('.no_camera').addClass('hide');
    $(document).find('.camera_li').removeClass('hide');
    if ($(document).find('.camera_li li:last-child a').length <= 0) {
        var next_index = 1;
        var next_active = 'active';
    } else {
        var next_index = parseInt($(document).find('.camera_li li:last-child a').data('camera').match(/\d+/)) + 1;
        var next_active = '';
    }
    if (next_index) {
        $(document).find('.camera_li').append('<li class="li_camera_' + next_index + ' ' + next_active + '"><a class="custom_tab" href="#camera_' + next_index + '" data-toggle="tab" data-camera="#camera_' + next_index + '">Camera ' + next_index + ' <i class="text-danger icon-minus-circle2 remove_camera"></i></a></li>');
        $(document).find('.camera_tab_content').append('<div class="tab-pane ' + next_active + '" id="camera_' + next_index + '" />');
        $('#camera_' + next_index).load(base_url + '/camera/manage_camera_tab', {index: next_index}, function () {

        });
    }
    e.preventDefault();
});

$(document).on('click', '.remove_camera', function () {
    var tab_remove = $(this).parent('a').attr('href');
    var camera_val = $(document).find('#remove_cameras').val();

    var tab_remove_li = $(this).parents('li');
    if (tab_remove) {
        if (tab_remove_li.hasClass('active')) {
            if ($(document).find('.camera_li li:first-child a').attr('href') == tab_remove) {
                tab_remove_li.next().addClass('active');
                $(document).find(tab_remove_li.next().find('a').attr('href')).addClass('active');
            } else {
                tab_remove_li.prev().addClass('active');
                $(document).find(tab_remove_li.prev().find('a').attr('href')).addClass('active');
            }
        }
        var camera_id = $(document).find(tab_remove).find('.camera_no').val();
        if (typeof camera_id != 'undefined') {
            camera_val = camera_val.concat(atob(camera_id), ',');
        }
        $(document).find('#remove_cameras').val(camera_val);
        $(document).find(tab_remove).remove();
        $(this).parents('li').remove();
        if ($(document).find('.camera_li li:last-child a').length <= 0) {
            $(document).find('.no_camera').removeClass('hide');
            $(document).find('.camera_li').addClass('hide');
        } else {
            $(document).find('.no_camera').addClass('hide');
            $(document).find('.camera_li').removeClass('hide');
        }
    }

});


jQuery(document).on('submit', "#manage_camera_frm", function (event) {
    add_camera('manage_camera_frm');
    event.preventDefault();
});

function add_camera(formid)
{
    $(document).find('.form-wrapper .icon-info22').remove();
    var camera_val = $(document).find('#remove_cameras').val();
    var lastChar = camera_val.slice(-1);
    if (lastChar == ',') {
        camera_val = camera_val.slice(0, -1);
    }
    $(document).find('#remove_cameras').val(camera_val);
    var data = new FormData($("#" + formid)[0]);
    jQuery('#' + formid + ' #camera_btn .spinner').removeClass('hide');
    jQuery.ajax({
        type: "POST",
        url: base_url + "camera/save",
        data: new FormData($("#" + formid)[0]),
        async: true,
        contentType: false,
        processData: false,
        success: function (data) {
            var json = jQuery.parseJSON(data);
            if (!json.status) {
                jQuery('#' + formid + ' #camera_btn .spinner').addClass('hide');
                high_light_tab(json.tabs);
                $('#' + formid + ' .userMessageDiv').html("");
                $('#' + formid + ' .userMessageDiv').html(json.msg);
                $('#' + formid + ' .userMessageDiv').show();
            } else {
                var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button class="close" data-dismiss="alert"><span>x</span></button>Camera(s) saved successfully. please wait while loading data.</div>';
                $('#' + formid + ' .userMessageDiv').html(msg);
                $('#' + formid + ' .userMessageDiv').show();
                jQuery('#' + formid + ' #camera_btn .spinner').addClass('hide');
                setTimeout(function () {
                    $('#' + formid + ' .userMessageDiv').hide();
                    jQuery('#modal_manage_camera').modal('hide');
                    window.location.href = base_url + 'camera';
                }, 2000);
            }
        }
    });
}

jQuery(document).on('click', ".delete_camera", function (event) {
    var camera_id = $(this).attr('data-camera');
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this!",
        type: "warning",
        confirmButtonText: "Yes, delete it!",
        showCancelButton: true,
        closeOnConfirm: false,
        showLoaderOnConfirm: true
    },
            function (isConfirm) {
                if (isConfirm) {
                    delete_camera(camera_id);
                    return true;
                } else {
                    return false;
                }
            });
    return false;
});
function delete_camera(camera_id) {
    $.ajax({
        url: base_url + 'camera/delete_camera',
        type: "post",
        data: {'id': camera_id},
        success: function (response) {
            var result = response.split("^");
            if (result[0] == "0") {
                swal("Not Deleted!", "Something Went wrong, Please try again.", "warning");
                setTimeout(function () {
                    $('.sa-button-container .confirm').trigger('click');
                }, 2000);
            } else {
                swal("Deleted!", "Camera deleted successfully.", "success");
                setTimeout(function () {
                    $('.sa-button-container .confirm').trigger('click');
                    window.location.href = base_url + 'camera';
                }, 1000);
            }
        }
    });
}