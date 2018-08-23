jQuery(document).on('click', ".delete_canned_response", function (event) {
    var cid = $(this).data('cid');
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
            delete_canned_response(cid);
            return true;
        } else {
            return false;
        }
    });
    return false;
});

function delete_canned_response(cid) {
    $.ajax({
        url: base_url + 'canned_responses/delete',
        type: "post",
        data: {'cid': cid},
        success: function (response)
        {
            var json = jQuery.parseJSON(response);
            if (!json.status) {
                swal("Deleted!", "Soething went wrong.Canned Response could not be deleted.", "warning");
            } else {
                $('#canned_responses_dt_table').DataTable().ajax.reload(null, false);
                swal("Deleted!", "Canned Response deleted successfully.", "success");
                setTimeout(function () {
                    $('.sa-button-container .confirm').trigger('click');
                }, 4000);
            }
        }
    });
}

jQuery(document).on('submit', "#canned_responses_frm", function (event) {
    save_canned_response('canned_responses_frm');
    event.preventDefault();
});

function save_canned_response(formid) {
    var data = jQuery("#" + formid).serialize();
    $(".userMessageDiv").hide();
    jQuery('#' + formid + ' .spinner').removeClass('hide');
    $(document).find('.frm-action button').attr('disabled', 'disabled');
    $.post(base_url + "canned_responses/save", data,
            function (data) {
                $(document).find('.frm-action button').removeAttr('disabled');
                jQuery('#' + formid + ' .spinner').addClass('hide');
                var result = data.split("^");
                if (result[0] == "0") {
                    $(".userMessageDiv").html("");
                    $(".userMessageDiv").html(result[1]);
                    $(".userMessageDiv").show();
                    jQuery('.save_canned_response_btn .spinner').addClass('hide');
                    $(document).find('.save_canned_response_btn').removeAttr('disabled');
                } else {
                    var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>x</span><span class="sr-only">Close</span></button>Canned response saved successfully.</div>';
                    $(".userMessageDiv").html(msg);
                    $(".userMessageDiv").show();
                    $("html, body").animate({scrollTop: 0}, "slow");
                    jQuery('.save_canned_response_btn .spinner').addClass('hide');
                    setTimeout(function () {
                        $(".userMessageDiv").hide();
                        $(document).find('.save_canned_response_btn').removeAttr('disabled');
                        window.location.href = base_url + 'canned_responses';
                    }, 2000);
                }

            });
}

$(document).ready(function () {

    $('#canned_responses_dt_table').dataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        language: {
            search: '<span>Search:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
            emptyTable: 'No canned responses available.'
        },
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        order: [[0, "asc"]],
        ajax: base_url + 'canned_responses/get',
        columns: [
            {
                data: "id",
                visible: true,
                sortable: false
            },
            {
                data: "title",
                visible: true,
            },
            {
                data: "subject",
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
                    str += '<li><a href="' + base_url + 'canned_responses/edit/' + btoa(full.id) + '"><i class="icon-pencil7"></i> Edit</a></li>';
                    str += '<li><a href="javascript:void(0);" data-cid=' + btoa(full.id) + ' " class="delete_canned_response"><i class="icon-trash"></i> Delete</a></li>';
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