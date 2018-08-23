// Enable Select2 select for individual column searching
$('.filter-select').select2();

function confirm_alert(e) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#FF7043",
        confirmButtonText: "Yes, delete it!"
    },
    function (isConfirm) {
        if (isConfirm) {
            window.location.href = $(e).attr('href');
            return true;
        }
        else {
            return false;
        }
    });
    return false;
}

jQuery(document).on('click', "#add_device", function (event) {    
    $(".select-folder").val('').trigger('change');
    $(document).find(".select-folder").select2({
        templateResult: iconFormat,
        minimumResultsForSearch: Infinity,
        templateSelection: iconFormat,
        escapeMarkup: function (m) {
            return m;
        }
    });
    $(document).find('#modal_add_device').modal('show');
});

jQuery(document).ready(function () {
    
    $(".switch").bootstrapSwitch();

    $('#folder_dt_table').dataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        language: {
            search: '<span>Search:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
            emptyTable: 'No Folder available.'
        },
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        order: [[0, "asc"]],
        ajax: base_url + 'upload/get',
        columns: [
            {
                data: "id",
                visible: true,
                sortable: false
            },
            {
                data: "name",
                visible: true,
                render: function (data, type, full, meta) {
                    return  '<a href="' + base_url + 'upload/view/' + btoa(full.id) + '" title="' + full.name + '"><i class="icon-folder-open"></i> ' + full.name + '</a>';
                },
            },
            {
                data: "user_name",
                visible: true,
            },
            {
                data: "created",
                visible: true,
                sortable: false
            },
            {
                data: "action",
                render: function (data, type, full, meta) {
                    var edit_url = base_url + 'upload/edit/' + btoa(full.id);
                    var str = '<ul class="icons-list"><li class="dropdown">';
                    str += '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a>';
                    str += '<ul class="dropdown-menu dropdown-menu-right">';
                    str += '<li><a href="' + edit_url + '"><i class="icon-pencil7"></i> Edit</a></li>';
                    str += '<li><a href="' + base_url + 'upload/delete/' + btoa(full.id) + '" class="" onclick="return confirm_alert(this)"><i class="icon-trash"></i> Delete</a></li>';
                    str += '<li><a href="' + base_url + 'upload/view/' + btoa(full.id) + '"><i class=" icon-zoomin3"></i> View</a></li>';
                    str += '</ul></li></ul>';
                    str += '</ul>';
                    return str;
                },
                sortable: false,
            },
        ]
    });

    $('#device_dt_table').dataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        language: {
            search: '<span>Search:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
            emptyTable: 'No Folder available.'
        },
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        order: [[0, "asc"]],
        ajax: base_url + 'devices/get',
        columns: [
            {
                data: "id",
                visible: true,
                sortable: false
            },
            {
                data: "code",
                visible: true,
            },
            {
                data: "hardware_id",
                visible: true,
                render: function (data, type, full, meta) {
                    var str = '';
                    if (full.hardware_id != '' && full.hardware_id != null) {
                        str = '<a href="' + base_url + 'devices/view/' + btoa(full.id) + '"><i class="icon-display4"></i> ' + full.hardware_id + '</a>';
                    }
                    return str;
                }
            },
            {
                data: "mac",
                visible: true,
                render: function (data, type, full, meta) {
                    var str = '';
                    if (full.mac != '' && full.mac != null) {
                        str = full.mac;
                    }
                    return str;
                }
            },
            {
                data: "device_name",
                visible: true,
                render: function (data, type, full, meta) {
                    var str = '';
                    if (full.device_name != '' && full.device_name != null) {
                        str = full.device_name;
                    }
                    return str;
                }
            },
            {
                data: "created",
                visible: true,
            },
            {
                data: "action",
                render: function (data, type, full, meta) {
                    var str = '<ul class="icons-list"><li class="dropdown">';
                    str += '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a>';
                    str += '<ul class="dropdown-menu dropdown-menu-right">';
                    str += '<li><a href="javascript:void(0);" id=' + btoa(full.id) + ' class="assign_folder"><i class="icon-plus-circle2"></i> Assign Folder</a></li>';
                    str += '<li><a href="' + base_url + 'devices/view/' + btoa(full.id) + '"><i class=" icon-zoomin3"></i> View</a></li>';
                    str += '<li><a href="' + base_url + 'devices/delete/' + btoa(full.id) + '" class="" onclick="return confirm_alert(this)"><i class="icon-trash"></i> Delete</a></li>';
                    str += '</ul></li></ul>';
                    str += '</ul>';
                    return str;
                },
                sortable: false,
            },
        ]
    });

    $('#template_dt_table').dataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        language: {
            search: '<span>Search:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
            emptyTable: 'No Folder available.'
        },
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        order: [[0, "asc"]],
        ajax: base_url + 'settings/get_templates',
        columns: [
            {
                data: "id",
                visible: true,
            },
            {
                data: "email_for",
                visible: true,
            },
            {
                data: "email_subject",
                visible: true,
            },
            {
                data: "action",
                render: function (data, type, full, meta) {
                    var str = '';
                    var checked = '';
                    if (full.is_enable == 1) {
                        checked = 'checked="checked"';
                    }
                    str += '<div style="display: inline-block;margin-right: 10px;"><a title="Edit" href="' + base_url + 'settings/templates_edit/' + btoa(full.id) + '"><i class="icon-pencil7"></i></a></div>';
                    str += '<div style="display: inline-block;" class=""><label><input type="checkbox" ' + checked + ' style="margin-top:0px;" id="' + btoa(full.id) + '" name="enable_send_email" data-on-color="primary" data-off-color="danger" data-on-text="Enable" data-off-text="Disable" class="switch TBSswitch hide"></label></div>';
                    return str;
                },
                sortable: false,
            },
        ],
        "drawCallback": function (settings) {
            $('.switch').bootstrapSwitch();
        }
    });

    jQuery("#template_dt_table").on('switchChange.bootstrapSwitch', '.TBSswitch', function (event, state) {
        var switch_id = this.id;
        $.ajax({
            url: base_url + 'settings/enable_template',
            type: "post",
            data: {'id': switch_id, 'is_enable': state},
            success: function (response)
            {
            }
        });
    });

    $('#users_dt_table').dataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        language: {
            search: '<span>Search:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
            emptyTable: 'No Folder available.'
        },
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        order: [[0, "asc"]],
        ajax: base_url + 'users/get',
        columns: [
            {
                data: "id",
                visible: true,
                sortable: false
            },
            {
                data: "user_name",
                visible: true,
            },
            {
                data: "status",
                visible: true,
                render: function (data, type, full, meta) {
                    var str = '';
                    if (full.status != '' && full.status != null) {
                        if (full.status == 'active') {
                            str = '<label class="label bg-success">Active</label>';
                        } else {
                            str = '<label class="label bg-danger">InActive</label>';
                        }
                    }
                    return str;
                }
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
                    str += '<li><a href="' + base_url + 'users/edit/' + btoa(full.id) + '"><i class="icon-pencil7"></i> Edit</a></li>';
                    str += '<li><a href="' + base_url + 'users/view/' + btoa(full.id) + '"><i class=" icon-zoomin3"></i> View</a></li>';
                    str += '<li><a href="' + base_url + 'users/delete/' + btoa(full.id) + '" class="" onclick="return confirm_alert(this)"><i class="icon-trash"></i> Delete</a></li>';
                    str += '</ul></li></ul>';
                    str += '</ul>';
                    str += '<a class="btn btn-primary pull-left" style="margin-left: 20px;"  href="' + base_url + 'users/autologin/' + btoa(full.id) + '">Auto Login</a>'
                    return str;
                },
                sortable: false,
            },
        ]
    });



// Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');


// Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });



});


// Format icon
function iconFormat(icon) {
    var originalOption = icon.element;
    if (!icon.id) {
        return icon.text;
    }
    var $icon = "<i class='icon-" + $(icon.element).data('icon') + "'></i>" + icon.text;

    return $icon;
}


jQuery(document).on('click', ".assign_folder", function (event) {

    jQuery.ajax({
        type: "POST",
        url: base_url + "devices/assign_folder/" + this.id,
        success: function (data) {
            $(document).find('.modal_div').html(data);
            $(document).find(".select-folder").select2({
                templateResult: iconFormat,
                minimumResultsForSearch: Infinity,
                templateSelection: iconFormat,
                escapeMarkup: function (m) {
                    return m;
                }
            });
            if ($(document).find('.modal_div').hasClass('dv_view')) {
                $(document).find('#modal_assign_folder').addClass('fv');
            }
            $(document).find('#modal_assign_folder').modal('show');
        }
    });
    event.preventDefault();
});

jQuery(document).on('click', ".delete_fi", function (event) {
    var id = this.id;
    $.ajax({
        url: base_url + 'upload/delete_folder_img',
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

jQuery(document).on('submit', "#create_folder_frm", function (event) {
    jQuery('#create_folder_frm .spinner').removeClass('hide');
    $(document).find('.frm-action button').attr('disabled', 'disabled');
    $(".userMessageDiv").html("");
    jQuery.ajax({
        type: "POST",
        url: base_url + "upload/save",
        data: new FormData($("#create_folder_frm")[0]),
        async: false,
        contentType: false,
        processData: false,
        success: function (data) {
            $(document).find('.frm-action button').removeAttr('disabled');
            jQuery('#create_folder_frm .spinner').addClass('hide');
            var result = data.split("^");
            $("html, body").animate({scrollTop: 0}, "slow");
            if (result[0] == "0") {
                $(".userMessageDiv").html("");
                $(".userMessageDiv").html(result[1]);
                $(".userMessageDiv").show();
            } else if (result[0] == "2") {
                $(".userMessageDiv").html("");
                $(".userMessageDiv").html(result[2]);
                $(".userMessageDiv").show();
                $("#create_folder_frm .folder_id").val(result[1]);
            } else if (result[0] == "3") {
                $(".userMessageDiv").html("");
                $(".userMessageDiv").html(result[2]);
                $(".userMessageDiv").show();
                $("#create_folder_frm .folder_id").val(result[1]);

                setTimeout(function () {
                    $(".userMessageDiv").hide();
                    window.location.href = base_url + 'upload'
                }, 2000);

            } else {
                var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>Folder saved successfully.</div>';
                $(".userMessageDiv").html(msg);
                $(".userMessageDiv").show();

                setTimeout(function () {
                    $(".userMessageDiv").hide();
                    window.location.href = base_url + 'upload'
                }, 2000);
            }
        }
    });
    event.preventDefault();
});

jQuery(document).on('submit', "#template_edit_frm", function (event) {
    jQuery('#template_edit_frm .spinner').removeClass('hide');
    $(document).find('.frm-action button').attr('disabled', 'disabled');
    $(".userMessageDiv").html("");
    var data = jQuery("#template_edit_frm").serialize();
    jQuery.ajax({
        type: "POST",
        url: base_url + "settings/save_tempalte",
        data: data,
        success: function (data) {
            $(document).find('.frm-action button').removeAttr('disabled');
            jQuery('#template_edit_frm .spinner').addClass('hide');
            var result = data.split("^");
            $("html, body").animate({scrollTop: 0}, "slow");
            if (result[0] == "0") {
                $(".userMessageDiv").html("");
                $(".userMessageDiv").html(result[1]);
                $(".userMessageDiv").show();
            } else {
                var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>Template saved successfully.</div>';
                $(".userMessageDiv").html(msg);
                $(".userMessageDiv").show();
                setTimeout(function () {
                    $(".userMessageDiv").hide();
                    window.location.href = base_url + 'settings/templates'
                }, 2000);
            }
        }
    });
    event.preventDefault();
});

jQuery(document).on('submit', "#send_email_config_frm", function (event) {
    jQuery('#send_email_config_frm .spinner').removeClass('hide');
    $(document).find('.frm-action button').attr('disabled', 'disabled');
    $(".userMessageDiv").html("");
    var data = jQuery("#send_email_config_frm").serialize();
    jQuery.ajax({
        type: "POST",
        url: base_url + "settings/save_email_config",
        data: data,
        success: function (data) {
            $(document).find('.frm-action button').removeAttr('disabled');
            jQuery('#send_email_config_frm .spinner').addClass('hide');
            var result = data.split("^");
            $("html, body").animate({scrollTop: 0}, "slow");
            if (result[0] == "0") {
                $(".userMessageDiv").html("");
                $(".userMessageDiv").html(result[1]);
                $(".userMessageDiv").show();
            } else {
                var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>Email Configuration saved successfully.</div>';
                $(".userMessageDiv").html(msg);
                $(".userMessageDiv").show();
                setTimeout(function () {
                    $(".userMessageDiv").hide();
                }, 2000);
            }
        }
    });
    event.preventDefault();
});

jQuery(document).on('submit', "#add_device_frm", function (event) {
    jQuery('#add_device_frm .spinner').removeClass('hide');
    $(document).find('.frm-action button').attr('disabled', 'disabled');
    $(".userMessageDiv").html("");
    var data = jQuery("#add_device_frm").serialize();
    jQuery.ajax({
        type: "POST",
        url: base_url + "devices/add",
        data: data,
        success: function (data) {
            $(document).find('.frm-action button').removeAttr('disabled');
            jQuery('#add_device_frm .spinner').addClass('hide');
            var result = data.split("^");
            $("html, body").animate({scrollTop: 0}, "slow");
            if (result[0] == "0") {
                $(".userMessageDiv").html("");
                $(".userMessageDiv").html(result[1]);
                $(".userMessageDiv").show();
            } else {
                var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>Device added successfully.</div>';
                $("#add_device_frm input").val('');
                $(".userMessageDiv").html(msg);
                $(".userMessageDiv").show();
                $('#device_dt_table').DataTable().ajax.reload(null, false);
                setTimeout(function () {
                    $(".userMessageDiv").hide();
                    $("#modal_add_device").modal('hide');
                }, 2000);
            }
        }
    });
    event.preventDefault();
});

jQuery(document).on('submit', "#assign_folder_frm", function (event) {
    jQuery('#assign_folder_frm .spinner').removeClass('hide');
    $(document).find('.frm-action button').attr('disabled', 'disabled');
    $(".userMessageDiv").html("");
    var data = jQuery("#assign_folder_frm").serialize();
    jQuery.ajax({
        type: "POST",
        url: base_url + "devices/save_assign_folder",
        data: data,
        success: function (data) {
            $(document).find('.frm-action button').removeAttr('disabled');
            jQuery('#assign_folder_frm .spinner').addClass('hide');
            var result = data.split("^");
            $("html, body").animate({scrollTop: 0}, "slow");
            if (result[0] == "0") {
                $(".userMessageDiv").html("");
                $(".userMessageDiv").html(result[1]);
                $(".userMessageDiv").show();
            } else {
                var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>Folder saved successfully.</div>';
                $(".userMessageDiv").html(msg);
                $(".userMessageDiv").show();
                $('#device_dt_table').DataTable().ajax.reload(null, false);
                setTimeout(function () {
                    $(".userMessageDiv").hide();
                    $(document).find("#modal_assign_folder").modal('hide');
                    if ($(document).find('#modal_assign_folder').hasClass('fv')) {
                        location.reload();
                    }
                }, 2000);
            }
        }
    });
    event.preventDefault();
});



