/* For managing services */
$('#services_dt_table').dataTable({
    autoWidth: false,
    processing: true,
    serverSide: true,
    language: {
        search: '<span>Search:</span> _INPUT_',
        lengthMenu: '<span>Show:</span> _MENU_',
        paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
        emptyTable: 'No services available.'
    },
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    order: [[0, "asc"]],
    ajax: base_url + 'services/get_services',
    columns: [
        {
            data: "id",
            visible: true,
            sortable: false
        },
        {
            data: "service_name",
            visible: true,
        },
        {
            data: "action",
            render: function (data, type, full, meta) {
                var str = '<ul class="icons-list"><li class="dropdown">';
                str += '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a>';
                str += '<ul class="dropdown-menu dropdown-menu-right">';
                str += '<li><a href="javascript:void(0);" data-service=' + btoa(full.id) + ' class="change_service"><i class="icon-pencil5"></i> Edit</a></li>';
                str += '<li><a href="javascript:void(0);" data-service=' + btoa(full.id) + ' class="delete_service"><i class="icon-trash"></i> Delete</a></li>';
                str += '</ul></li></ul>';
                str += '</ul>';
                return str;
            },
            sortable: false,
        },
    ],
});
datatable_select2();
jQuery(document).on('click', "#add_service", function (event) {
    $(".select-folder").val('').trigger('change');
    $(".modal_manage_service").load(base_url + 'services/load_service', function () {
        jQuery('#modal_manage_service').modal('show');
    });
});
jQuery(document).on('click', ".change_service", function (event) {
    var service_id = $(this).attr('data-service');
    $(".modal_manage_service").load(base_url + 'services/load_service/' + service_id, function () {
        jQuery('#modal_manage_service').modal('show');
    });
});
jQuery(document).on('click', "#add_service_btn", function (event) {
    jQuery('#add_service_btn .spinner').removeClass('hide');
    $(document).find('#add_service_btn').attr('disabled', 'disabled');
    $(".userMessageDiv").html("");
    var data = jQuery("#service_frm").serialize();
    jQuery.ajax({
        type: "POST",
        url: base_url + "services/save_service",
        data: data,
        success: function (data) {
            $(document).find('#add_service_btn').removeAttr('disabled');
            var result = data.split("^");
//            $("html, body").animate({scrollTop: 0}, "slow");
            if (result[0] == "0") {
                $(".userMessageDiv").html("");
                $(".userMessageDiv").html(result[1]);
                $(".userMessageDiv").show();
                jQuery('#add_service_btn .spinner').addClass('hide');
                $(document).find('#add_service_btn').removeAttr('disabled');
            } else {
                var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>x</span><span class="sr-only">Close</span></button>Service saved successfully.</div>';
                $(".userMessageDiv").html(msg);
                $(".userMessageDiv").show();
                setTimeout(function () {
                    $(".userMessageDiv").hide();
                    $("#modal_manage_service").modal('hide');
                    jQuery('#add_service_btn .spinner').addClass('hide');
                    $(document).find('#add_service_btn').removeAttr('disabled');
                    $('#services_dt_table').DataTable().ajax.reload(null, false);
                }, 2000);
            }
        }
    });
    event.preventDefault();
});
jQuery(document).on('click', ".delete_service", function (event) {
    var service = $(this).data('service');
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
                    delete_service(service);
                    return true;
                } else {
                    return false;
                }
            });
    return false;
});
function delete_service(service_id) {
    $.ajax({
        url: base_url + 'services/delete_service',
        type: "post",
        data: {'service_id': service_id},
        success: function (response)
        {
            var json = jQuery.parseJSON(response);
            if (!json.status) {
                $(document).find('.modal_manage_service').html(json.service_packages);
                $('.cancel').trigger('click');
                $(document).find('#modal_service_package').modal('show');
                $(document).find('#dt_list_service_package').DataTable();
                datatable_select2();
            } else {
                $('#services_dt_table').DataTable().ajax.reload(null, false);
                swal("Deleted!", "Service deleted successfully.", "success");
                setTimeout(function () {
                    $('.sa-button-container .confirm').trigger('click');
                }, 4000);
            }
        }
    });
}