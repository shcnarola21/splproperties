// Enable Select2 select for individual column searching
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
                } else {
                    return false;
                }
            });
    return false;
}
if (typeof (pay_customer) === "undefined") {
    pay_customer = '';
}
jQuery(document).ready(function () {

    $(".switch").bootstrapSwitch();
    select2_dropdown();
    select2_dropdown_with_search();
    $('#providers_dt_table').dataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        language: {
            search: '<span>Search:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
            emptyTable: 'No Providers available.'
        },
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        order: [[0, "asc"]],
        ajax: base_url + 'providers/get',
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
                data: "email",
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
                    str += '<li><a href="' + base_url + 'providers/edit/' + btoa(full.id) + '"><i class="icon-pencil7"></i> Edit</a></li>';
                    str += '<li><a href="' + base_url + 'providers/view/' + btoa(full.id) + '"><i class=" icon-zoomin3"></i> View</a></li>';
                    str += '<li><a href="' + base_url + 'providers/delete/' + btoa(full.id) + '" class="" onclick="return confirm_alert(this)"><i class="icon-trash"></i> Delete</a></li>';
                    str += '</ul></li></ul>';
                    str += '</ul>';
                    str += '<a class="btn btn-primary pull-left" style="margin-left: 20px;"  href="' + base_url + 'users/autologin/' + btoa(full.id) + '">Auto Login</a>'
                    return str;
                },
                sortable: false,
            },
        ]
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
                data: "sr_no",
                visible: true,
                sortable: false
            },
            {
                data: "user_name",
                visible: true,
            },
            {
                data: "email",
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
                    ///str += '<a class="btn btn-primary pull-left" style="margin-left: 20px;"  href="' + base_url + 'users/autologin/' + btoa(full.id) + '">Auto Login</a>'
                    return str;
                },
                sortable: false,
            },
        ]
    });
   datatable_select2();
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


jQuery(document).on('click', ".custom_close", function (e) {
    var id = $(this).parents('.modal').attr('id');
//    remove_modal_backdrop();
    $('#' + id).on('hidden.bs.modal', function () {
        $('#' + id).parent('div').html('');
    });
});

function remove_modal(id) {
    $('#' + id).on('hidden.bs.modal', function () {
        $('#' + id).parent('div').html('');
    });
}

$.fn.focusTextToEnd = function () {
    this.focus();
    var $thisVal = this.val();
    this.val('').val($thisVal);
    return this;
}

function remove_modal_backdrop() {
    if ($(document).find('.modal-backdrop').length > 0) {
        $(document).find('.modal-backdrop').remove();
    }
}
function styled_checkbox() {
    $(".styled").uniform({
        radioClass: 'choice'
    });
}
function tag_it() {
    $('.tokenfield').tokenfield();
//    $('.tokenfield .token-label').removeAttr('style');
}

function select2_dropdown() {
    $('.select').select2({
        minimumResultsForSearch: -1
    });
}
function select2_dropdown_with_search() {
    $('.select-search').select2();
}
function datatable_select2() {
    $('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
}

//Common 
function high_light_tab(tabs) {
    if (tabs != '') {
        $.each(tabs, function (key, value) {
            $(document).find('.form-wrapper:visible .li_' + value + ' a').prepend('<i class="text-danger icon-info22"></i> ');
        });
    }
}
