if (typeof property_id == 'undefined') {
    property_id = '';
}
function getNextAngle(nextAngle, id) {
    nextAngle += 90;
    if (nextAngle >= 360) {
        nextAngle = 0;
    }

    $(document).find("#" + id).data('nextangle', nextAngle);
    return nextAngle;
}

jQuery(document).on('click', ".rotate_img", function (event) {
    var id = this.id;
    var nextAngle = $(this).data('nextangle');
    var next_angle_val = getNextAngle(nextAngle, id);
    $(document).find("#img_" + id).rotate(next_angle_val);
    jQuery.ajax({
        type: "POST",
        url: base_url + "properties/rotate_img/" + this.id,
        data: {"degree": next_angle_val},
        success: function (data) {

        }
    });
});
jQuery(document).on('click', ".delete_fi", function (event) {
    var id = this.id;
    $.ajax({
        url: base_url + 'properties/delete_property_img',
        type: "post",
        data: {'id': id},
        success: function (response)
        {
            if (response) {
                $('.f_' + id).fadeOut(300, function () {
                    $(this).remove();
                });
            }
        }
    });
    event.preventDefault();
});
$(document).find('.uploader')
        .bind('dragenter', function (ev) {
            $(this).addClass('dragover');
            return false;
        })
        .bind('dragleave', function (ev) {
            $(this).removeClass('dragover');
            return false;
        })
        .bind('dragover', function (ev) {
            return false;
        })
        .bind('drop', function (ev) {
            var dt = ev.originalEvent.dataTransfer;
            var file = dt.files;
            var imageLoader = document.getElementById('upload_file_view');
            imageLoader.files = file;
            if (navigator.userAgent.match(/firefox/i)) {
                $("#upload_file_view").trigger('change');
            }
            ev.preventDefault();
            ev.stopPropagation();
            return false;
        });
$(document).find('.agreement_uploader')
        .bind('dragenter', function (ev) {
            $(this).addClass('dragover');
            return false;
        })
        .bind('dragleave', function (ev) {
            $(this).removeClass('dragover');
            return false;
        })
        .bind('dragover', function (ev) {
            return false;
        })
        .bind('drop', function (ev) {
            var dt = ev.originalEvent.dataTransfer;
            var file = dt.files;
            var imageLoader = document.getElementById('general_agreement');
            imageLoader.files = file;
            if (navigator.userAgent.match(/firefox/i)) {
                $("#general_agreement").trigger('change');
            }
            ev.preventDefault();
            ev.stopPropagation();
            return false;
        });
jQuery(document).on('change', "#upload_file_view", function (event) {
    $("#view_file_upload_frm").submit();
});
$(document).ready(function () {

    $(".prop_images").fileinput({
        uploadAsync: false,
        showRemove: true,
        showUpload: false,
        initialPreview: [],
        allowedFileExtensions: ['jpg', 'png', 'gif'],
        browseLabel: "Add Photo(s)",
        fileActionSettings: {
            removeIcon: '<i class="icon-bin"></i>',
            removeClass: 'btn btn-link btn-xs btn-icon',
            uploadIcon: '<i class="icon-upload"></i>',
            uploadClass: 'btn btn-link btn-xs btn-icon',
            indicatorNew: '<i class="icon-file-plus text-slate"></i>',
            indicatorSuccess: '<i class="icon-checkmark3 file-icon-large text-success"></i>',
            indicatorError: '<i class="icon-cross2 text-danger"></i>',
            indicatorLoading: '<i class="icon-spinner2 spinner text-muted"></i>',
        }
    });
    load_image();
});
function load_image() {
    $('.preview_img img').each(function () {
        var this_image = this;
        var src = $(this_image).attr('src') || '';
        if (!src.length > 0) {
            var lsrc = $(this_image).attr('lsrc') || '';
            if (lsrc.length > 0) {
                var img = new Image();
                img.src = lsrc;
                $(img).load(function () {
                    this_image.src = this.src;
                    $(this_image).parent().next().find('h6').removeClass('vhide');
                    $(this_image).removeClass('loading');
                });
            }
        }
    });
}

jQuery(document).on('click', ".btn_file_input_view,.icon_plus", function (event) {
    $("#upload_file_view").trigger('click');
});
jQuery(document).on('click', ".btn_general_agreement,.icon_agreement_plus", function (event) {
    $("#general_agreement").trigger('click');
});
jQuery(document).on('click', "#button", function (event) {
    $(document).find(".north").rotate(getNextAngle());
});
//jQuery(document).on('submit', "#view_file_upload_frm", function (event) {

var cnt = 0;
formData = new FormData($("#img_form")[0]);
var data = {};
jQuery(document).on('change', "#upload_file_view", function (event) {
    var upload_form_data = new FormData($("#property_frm")[0]);
    upload_form_data.append('file_type', 'image');
    $("#view_img_file_input").removeClass('icon_plus');
    $('.btn_file_input_view').attr("disabled", true);
    $('.btn_file_input_view').html('<i class="icon-spinner9 spinner position-left"></i> Uploading...')

    var action = $(this).data('action');
    if (action == 'edit') {
        jQuery.ajax({
            type: "POST",
            url: base_url + "properties/upload_file",
            data: upload_form_data,
            async: true,
            contentType: false,
            processData: false,
            success: function (data) {
                $("#view_img_file_input").addClass('icon_plus');
                $('.btn_file_input_view').attr("disabled", false);
                $('.btn_file_input_view').html('<i class="icon-images3"></i> Add Photo(s)');
                var json = $.parseJSON(data);
                var msg = 'Invalid File!';
                if (json.status) {
                    $(".cls_upload_div").before(json.html);
                    load_image();
                } else {
                    swal(msg, json.msg, "warning");
                }
            }
        });
    } else {
        $.each($("input[name='upload_file_view[]']")[0].files, function (i, file) {
            formData.append('all_files[]', file);
            console.log(formData.getAll('all_files'));
        });
        var files = event.target.files;
        var filesLength = files.length;
        for (var i = 0; i < filesLength; i++) {
            var f = files[i];
            var fileReader = new FileReader();
            fileReader.onload = (function (e, v) {
                var html = '<div class="col-lg-2 img-wrapper">';
                html += ' <div class="thumbnail">';
                html += '<div class="thumb">';
                html += '<img class="loading img_view_wrapper" src="" lsrc="' + e.target.result + '"  alt="">';
                html += '<div class="caption-overflow">';
                html += '<span>';
                html += '<a href="' + e.target.result + '" data-popup="lightbox" rel="gallery" class="btn border-white text-white btn-flat btn-icon btn-rounded"><i class="icon-zoomin3"></i></a>';
                html += '</span>';
                html += '</div>';
                html += '</div>';
                html += '<div class="caption">';
                html += '<h6 class="no-margin">';
                html += '<a href="" class="text-default" ">&nbsp;</a>';
                html += '<a href="" class="text-default pull-left" ">&nbsp;</a>';
                html += '</h6>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                $(".preview_img").prepend(html);
                cnt++;
                load_image();
            });
            fileReader.readAsDataURL(f);
            $("#view_img_file_input").addClass('icon_plus');
            $('.btn_file_input_view').attr("disabled", false);
            $('.btn_file_input_view').html('<i class="icon-images3"></i> Add Photo(s)');
        }
    }
    event.preventDefault();
});

jQuery(document).on('change', "#general_agreement", function (event) {
    $(".icon_general_agreement").removeClass('icon_plus');
    $('.btn_general_agreement').attr("disabled", true);
    $('.btn_general_agreement').html('<i class="icon-spinner9 spinner position-left"></i> Uploading...');
    var agreement_form_data = new FormData($("#property_frm")[0]);
    agreement_form_data.append('file_type', 'agreement');

    var action = $(this).data('action');
    if (action == 'edit') {
        jQuery.ajax({
            type: "POST",
            url: base_url + "properties/upload_file",
            data: agreement_form_data,
            async: true,
            contentType: false,
            processData: false,
            success: function (data) {
                $(".icon_general_agreement").addClass('icon_plus');
                $('.btn_general_agreement').attr("disabled", false);
                $('.btn_general_agreement').html('<i class="icon-images3"></i> Add Photo(s)');
                var json = $.parseJSON(data);
                var msg = 'Invalid File!';
                if (json.status) {
                    $(".cls_general_agreement_div").before(json.html);
                    load_image();
                } else {
                    swal(msg, json.msg, "warning");
                }
            }
        });
    }
    event.preventDefault();
});

jQuery(document).on('click', ".delete_property", function (event) {
    var pid = $(this).data('pid');
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
                    delete_property(pid);
                    return true;
                } else {
                    return false;
                }
            });
    return false;
});
function delete_property(pid) {
    $.ajax({
        url: base_url + 'properties/delete',
        type: "post",
        data: {'pid': pid},
        success: function (response)
        {
            var json = jQuery.parseJSON(response);
            if (!json.status) {
                swal("Deleted!", "Soething went wrong.Property could not be deleted.", "warning");
            } else {
                $('#properties_dt_table').DataTable().ajax.reload(null, false);
                swal("Deleted!", "Property deleted successfully.", "success");
                setTimeout(function () {
                    $('.sa-button-container .confirm').trigger('click');
                }, 4000);
            }
        }
    });
}

jQuery(document).on('click', "#add_property_btn", function (event) {
    jQuery('#add_property_btn .spinner').removeClass('hide');
    $(document).find('#add_property_btn').attr('disabled', 'disabled');
    $(".userMessageDiv").html("");
    for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }
    var poData = $(document).find("#property_frm").serializeArray();
    for (var i = 0; i < poData.length; i++)
        formData.append(poData[i].name, poData[i].value);
    //var data = new FormData($(document).find("#property_frm")[0]);

    jQuery.ajax({
        type: "POST",
        url: base_url + "properties/save",
        data: formData,
        async: true,
        contentType: false,
        processData: false,
        success: function (data) {
            $(document).find('#add_property_btn').removeAttr('disabled');
            var result = data.split("^");
            if (result[0] == "0") {
                $(".userMessageDiv").html("");
                $(".userMessageDiv").html(result[1]);
                $(".userMessageDiv").show();
                jQuery('#add_property_btn .spinner').addClass('hide');
                $(document).find('#add_property_btn').removeAttr('disabled');
            } else {
                var msg_txt = 'Property saved successfully.';
                if (result[0] == "2") {
                    msg_txt += 'Property images could not be saved.';
                }
                var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>x</span><span class="sr-only">Close</span></button>' + msg_txt + '</div>';
                $(".userMessageDiv").html(msg);
                $(".userMessageDiv").show();
                jQuery('#add_property_btn .spinner').addClass('hide');
                setTimeout(function () {
                    $(".userMessageDiv").hide();
                    $(document).find('#add_property_btn').removeAttr('disabled');
                    window.location.href = base_url + 'properties'
                }, 2000);
            }
        }
    });
    event.preventDefault();
});
$(document).ready(function () {
    $('#properties_dt_table').dataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        language: {
            search: '<span>Search:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
            emptyTable: 'No Properties available.'
        },
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        order: [[0, "asc"]],
        ajax: base_url + 'properties/get',
        columns: [
            {
                data: "id",
                visible: true,
                sortable: false
            },
            {
                data: "address",
                visible: true,
                render: function (data, type, full, meta) {
                    var str = "";
                    if (full.address != "") {
                        str += full.address + ', ';
                    }
                    if (full.city != "") {
                        str += full.city + ', ';
                    }
                    if (full.state != "") {
                        str += full.state + ', ';
                    }
                    if (full.zip_code != "") {
                        str += full.zip_code + ', ';
                    }
                    if (full.country != "") {
                        str += full.country;
                    }
                    return str;
                }
            },
            {
                data: "units",
                visible: true,
            },
            {
                data: "created",
                visible: true,
            },
            {
                data: "action",
                render: function (data, type, full, meta) {
                    var str = '';
                    str += '<ul class="icons-list pull-left"><li class="dropdown">';
                    str += '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a>';
                    str += '<ul class="dropdown-menu dropdown-menu-right">';
                    str += '<li><a href="' + base_url + 'properties/edit/' + btoa(full.id) + '" data-pid=' + btoa(full.id) + ' class="edit_property"><i class="icon-pencil7"></i> Edit/View</a></li>';
                    str += '<li><a href="javascript:void(0);" data-pid=' + btoa(full.id) + ' " class="delete_property"><i class="icon-trash"></i> Delete</a></li>';
                    str += '</ul></li></ul>';
                    str += '</ul>';
                    return str;
                },
                sortable: false,
            },
        ]
    });
    datatable_select2();
});

//For Property Zone
if ($(document).find("#property_zones_dttable").length > 0) {
    $('#property_zones_dttable').dataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        language: {
            search: '<span>Search:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
            emptyTable: 'No zones available.'
        },
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        order: [[0, "asc"]],
        ajax: base_url + 'properties/get_zone/' + property_id,
        columns: [
            {
                "data": "id",
                width: "5%",
                "visible": true
            },
            {
                "data": "name",
                width: "30%",
                "visible": true
            },
            {
                "data": "password",
                width: "20%",
                "visible": true
            },
            {
                "visible": true,
                width: "5%",
                "sortable": false,
                render: function (data, type, full, meta) {
                    var str = '<ul class="icons-list"><li class="dropdown">';
                    str += '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a>';
                    str += '<ul class="dropdown-menu dropdown-menu-right">';
                    str += '<li><a href="javascript:void(0);" data-zone=' + btoa(full.id) + ' class="edit_property_zone"><i class="icon-pencil5"></i> Edit</a></li>';
                    str += '<li><a href="javascript:void(0);" data-zone=' + btoa(full.id) + ' class="delete_property_zone"><i class="icon-trash"></i> Delete</a></li>';
                    str += '</ul></li></ul>';
                    str += '</ul>';
                    return str;
                },
            }
        ],
    });
}

jQuery(document).on('click', ".add_property_zone", function (e) {
    $(".manage_zones").load(base_url + 'properties/load_manage_zone', {property: property_id}, function () {
        jQuery('#modal_manage_zone').modal('show');
    });
    e.preventDefault();
});

jQuery(document).on('click', ".edit_property_zone", function (e) {
    var zone_id = $(this).data('zone');
    $(".manage_zones").load(base_url + 'properties/load_manage_zone', {zone: zone_id, property: property_id}, function () {
        jQuery('#modal_manage_zone').modal('show');
    });
    e.preventDefault();
});

jQuery(document).on('submit', "#manage_zone_frm", function (event) {
    console.log("in click");
    add_zone('manage_zone_frm');
    event.preventDefault();
});
function add_zone(formid) {
    $(document).find("#" + formid + ' .btn_zone .spinner').removeClass('hide');
    $(document).find("#" + formid + ' .form-actions button').attr('disabled', 'disabled');
    var data = new FormData($(document).find("#" + formid)[0]);
    jQuery.ajax({
        type: "POST",
        url: base_url + "properties/manage_zone",
        data: data,
        async: true,
        contentType: false,
        processData: false,
        success: function (data) {
            var result = data.split("^");
            if (result[0] == "0") {
                $(document).find("#" + formid + " .form-actions button").removeAttr('disabled');
                $(document).find("#" + formid + " .btn_zone .spinner").addClass('hide');
                $("#" + formid + " .userMessageDiv").html("");
                $("#" + formid + " .userMessageDiv").html(result[1]);
                $("#" + formid + " .userMessageDiv").show();
            } else if (result[0] == "2") {
                $(document).find("#" + formid + " #contract").val(result[1]);
                $(document).find("#" + formid + " .form-actions button").removeAttr('disabled');
                $("#" + formid + " .btn_zone .spinner").addClass('hide');
                $("#" + formid + " .userMessageDiv").html("");
                $("#" + formid + " .userMessageDiv").html(result[2]);
                $("#" + formid + " .userMessageDiv").show();
            } else {
                $(document).find("#" + formid + " .btn_zone .spinner").addClass('hide');
                var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button class="close" data-dismiss="alert"></button>Property zone saved successfully. please wait while loading data in table.</div>';
                $("#" + formid + " .userMessageDiv").html(msg);
                $("#" + formid + " .userMessageDiv").show();
                setTimeout(function () {
                    $('#property_zones_dttable').DataTable().ajax.reload(null, false);
                    $("#" + formid + " .userMessageDiv").hide();
                    jQuery('#modal_manage_zone').modal('hide');
                    $(document).find("#" + formid + " .form-actions button").removeAttr('disabled');
                    jQuery("#" + formid)[0].reset();
                }, 2000);
            }
        }
    });
    return false;
}

jQuery(document).on('click', ".delete_property_zone", function (event) {
    var zone_id = $(this).data('zone');
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
                    delete_zone(zone_id);
                    return true;
                } else {
                    return false;
                }
            });
    return false;
});
function delete_zone(zone_id) {
    $.ajax({
        url: base_url + 'properties/delete_zone',
        type: "post",
        data: {'zone': zone_id},
        success: function (response)
        {
            var json = jQuery.parseJSON(response);
            if (!json.status) {
                $(document).find('.modal_keyless_accesses_zone').html(json.keyless_accesses);
                $('.cancel').trigger('click');
                $(document).find('#modal_keyless_accesses_zone').modal('show');
                $(document).find('#dt_keyless_accesses_zone').DataTable();
                datatable_select2();
            } else {
                $(document).find('.modal_keyless_accesses_zone').html('');
                $('#property_zones_dttable').DataTable().ajax.reload(null, false);
                swal("Deleted!", "Property Zone deleted successfully.", "success");
                setTimeout(function () {
                    $('.sa-button-container .confirm').trigger('click');
                }, 4000);
            }
        }
    });
}

jQuery(document).on('click', ".manage_send_general_agreement", function () {
    $(".manage_send_general_agreement_content").load(base_url + 'properties/send_general_agreement_content', {property: property_id}, function () {
        styled_checkbox();
        CKEDITOR.replace('message', {
            height: 'auto',
            extraPlugins: 'forms'
        });
        tag_it();
        $('#send_agreement_modal').modal('show');
    });
});


jQuery(document).on('click', ".btn_send_agreement", function (event) {
    var formid = 'send_agreement_frm';
    for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }
    var data1 = $(document).find("#" + formid).serialize();
    var data = new FormData($(document).find("#" + formid)[0]);
    console.log("data", data1);
    $("#" + formid + " .userMessageDiv").hide();
    jQuery('#' + formid + ' .spinner').removeClass('hide');
    $(document).find('.btn_send_agreement .frm-action button').attr('disabled', 'disabled');
//    $.post(base_url + "properties/send_agreement_to_customer", data,
//            function (data) {
    jQuery.ajax({
        type: "POST",
        url: base_url + "properties/send_agreement_to_customer",
        data: data,
        async: true,
        contentType: false,
        processData: false,
        success: function (data) {
            $(document).find('.btn_send_agreement .frm-action button').removeAttr('disabled');
            jQuery('#' + formid + ' .spinner').addClass('hide');
            var result = data.split("^");
            if (result[0] == "0") {
                $("#" + formid + " .userMessageDiv").html("");
                $("#" + formid + " .userMessageDiv").html(result[1]);
                $("#" + formid + " .userMessageDiv").show();
                $("#" + formid + " .spinner").addClass('hide');
            } else {
                var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>x</span><span class="sr-only">Close</span></button>Notification sent to customer successfully.</div>';
                $("#" + formid + " .userMessageDiv").html(msg);
                $("#" + formid + " .userMessageDiv").show();
                $("html, body").animate({scrollTop: 0}, "slow");
                $("#" + formid + " .spinner").addClass('hide');
                setTimeout(function () {
                    $('#send_agreement_modal').modal('hide');
                    $("#" + formid + " .userMessageDiv").hide();
                }, 2000);
            }

        }
    });
    event.preventDefault();
});
