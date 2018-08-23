$('#todolist_dt_table').dataTable({
    autoWidth: false,
    processing: true,
    serverSide: true,
    language: {
        search: '<span>Search:</span> _INPUT_',
        lengthMenu: '<span>Show:</span> _MENU_',
        paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
        emptyTable: 'No Maintenace / Repair(s) notes available.'
    },
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    order: [[0, "asc"]],
    ajax: base_url + 'todolist/get',
    columns: [
        {
            "data": "id",
            width: "5%",
            "visible": true
        },
        {
            "data": "description",
            width: "20%",
            "visible": true
        },
        {
            "data": "status",
            width: "10%",
            "visible": true
        },
        {
            "data": "created",
            width: "10%",
            "visible": true
        },
        {
            "visible": true,
            width: "8%",
            "sortable": false,
            render: function (data, type, full, meta) {
                var str = '<ul class="icons-list"><li class="dropdown">';
                str += '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a>';
                str += '<ul class="dropdown-menu dropdown-menu-right">';
                str += '<li><a href="javascript:void(0);" data-todolist=' + btoa(full.id) + ' class="edit_todolist"><i class="icon-pencil5"></i> Edit</a></li>';
                str += '<li><a href="javascript:void(0);" data-todolist=' + btoa(full.id) + ' class="delete_todolist"><i class="icon-trash"></i> Delete</a></li>';
                str += '</ul></li></ul>';
                str += '</ul>';
                return str;
            },
        }
    ],
});

jQuery(document).on('click', ".add_todolist", function (e) {
    $(".todolist_div").load(base_url + 'todolist/load_manage_todolist', function () {
        jQuery('#modal_manage_todolist').modal('show');
        select2_dropdown();
    });
    e.preventDefault();
});
jQuery(document).on('click', ".edit_todolist", function (event) {
    var todolist_id = $(this).attr('data-todolist');
    $(".todolist_div").load(base_url + 'todolist/load_manage_todolist', {todolist: todolist_id}, function () {
        jQuery('#modal_manage_todolist').modal('show');
        select2_dropdown();
    });
});

jQuery(document).on('submit', "#manage_todolist_frm", function (event) {
    add_todolist('manage_todolist_frm');
    event.preventDefault();
});
function add_todolist(formid)
{
    var data = jQuery("#" + formid).serialize();
    $("#" + formid + " .btn_todolist .spinner").removeClass('hide');
    $(document).find("#" + formid + " .form-actions button").attr('disabled', 'disabled');
    jQuery.ajax({
        type: "POST",
        url: base_url + "todolist/save",
        data: data,
        success: function (data) {
            var result = data.split("^");
            if (result[0] == "0") {
                $(document).find("#" + formid + " .form-actions button").removeAttr('disabled');
                $("#" + formid + " .btn_todolist .spinner").addClass('hide');
                $("#" + formid + " .userMessageDiv").html("");
                $("#" + formid + " .userMessageDiv").html(result[1]);
                $("#" + formid + " .userMessageDiv").show();
            } else {
                $("#" + formid + " .btn_todolist .spinner").addClass('hide');
                var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button class="close" data-dismiss="alert"></button>Maintenace / Repair(s) note saved successfully. please wait while loading data in table.</div>';
                $("#" + formid + " .userMessageDiv").html(msg);
                $("#" + formid + " .userMessageDiv").show();
                setTimeout(function () {
                    $('#todolist_dt_table').DataTable().ajax.reload(null, false);
                    $("#" + formid + " .userMessageDiv").hide();
                    jQuery('#modal_manage_todolist').modal('hide');
                    remove_modal_backdrop();
                    $(document).find("#" + formid + " .form-actions button").removeAttr('disabled');
                    jQuery("#" + formid)[0].reset();
                }, 2000);
            }
        }
    });
}



jQuery(document).on('click', ".delete_todolist", function (event) {
    var todolist_id = $(this).attr('data-todolist');
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
                    delete_todolist(todolist_id);
                    return true;
                } else {
                    return false;
                }
            });
    return false;
});
function delete_todolist(todolist_id) {
    $.ajax({
        url: base_url + 'todolist/delete_todolist',
        type: "post",
        data: {'id': todolist_id},
        success: function (response) {
            var result = response.split("^");
            if (result[0] == "0") {
                swal("Not Deleted!", "Something Went wrong, Please try again.", "warning");
                setTimeout(function () {
                    $('.sa-button-container .confirm').trigger('click');
                }, 2000);
            } else {
                $('#todolist_dt_table').DataTable().ajax.reload(null, false);
                swal("Deleted!", "Note deleted successfully.", "success");
                setTimeout(function () {
                    $('.sa-button-container .confirm').trigger('click');
                }, 2000);
            }
        }
    });
}