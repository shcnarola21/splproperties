$(document).ready(function () {
    $('#templates_dt_table').dataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        language: {
            search: '<span>Search:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
            emptyTable: 'No Templates available.'
        },
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        order: [[0, "asc"]],
        ajax: base_url + 'templates/get',
        columns: [
            {
                data: "id",
                visible: true,
                sortable: false
            },
            {
                data: "name",
                visible: true,
            },
            {
                data: "subject",
                visible: true,
            },
            {
                data: "type",
                "bVisible": false
            },
            {
                data: "action",
                render: function (data, type, full, meta) {
                    var checked = '';
                    if (full.is_enable == '1') {
                        checked = 'checked="checked"';
                    }
                    var str = '';
                    str += '<ul class="icons-list pull-left"><li class="dropdown">';
                    str += '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a>';
                    str += '<ul class="dropdown-menu dropdown-menu-right">';
                    str += '<li><a href="' + base_url + 'templates/edit/' + btoa(full.id) + '"><i class="icon-pencil7"></i> Edit</a></li>';
                    str += '<li><a href="javascript:void(0)" class="preview" data-type="' + full.type + '" data-tid="' + btoa(full.id) + '" ><i class="icon-zoomin3"></i> Preview</a></li>';
                    str += '</ul></li></ul>';
                    str += '<div class="checkbox checkbox-switchery">';
                    str += '<label>';
                    str += '<input type="checkbox" name="is_enable" ' + checked + '  data-tid="' + btoa(full.id) + '" value="1" class="hide switchery-primary update_status">';
                    str += '</label>';
                    str += '</div>';

                    str += '</ul>';
                    return str;
                },
                sortable: false,
            },
        ],
        "drawCallback": function (settings) {
            var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery-primary'));
            elems.forEach(function (html) {
                var switchery = new Switchery(html);
            });
            var api = this.api();
            var rows = api.rows({page: 'current'}).nodes();
            var last = null;


            api.column(3, {page: 'current'}).data().each(function (group, i) {
                if (last !== group) {
                    $(rows).eq(i).before(
                            '<tr class="group"><td colspan="4">' + group + '</td></tr>'
                            );

                    last = group;
                }
            });
        },
    });
    datatable_select2();
});

jQuery(document).on('click', ".update_status", function (event) {
    var checked = false;
    var tid = $(this).data('tid');
    if ($(this).is(':checked')) {
        checked = true;
    }
    $.ajax({
        url: base_url + 'templates/update_status',
        type: "post",
        data: {'tid': tid, 'checked': checked},
        success: function (response)
        {
        }
    });
});
jQuery(document).on('click', ".delete_ti", function (event) {
    var id = this.id;
    $.ajax({
        url: base_url + 'templates/delete_template_img',
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


jQuery(document).on('click', ".preview", function (event) {
    var tid = $(this).data('tid');
    var type = $(this).data('type');
    if (type == 'PDF') {        
        $('#pdf_iframe').attr('src', base_url + "templates/preview/"+tid + '#zoom=100');
        $(".pdf_template_preview_modal").modal('show');       
    } else {        
        $(".modal_div").load(base_url + 'templates/preview/' + tid, function () {
            $(".template_preview_modal .template_loader").hide();
            $(".template_preview_modal .template_content").show();
            $(".template_preview_modal").modal('show');
        });
    }

});


jQuery(document).on('submit', "#template_frm", function (event) {
    edit_templates('template_frm');
    event.preventDefault();
});

function edit_templates(formid) {
    $(".userMessageDiv").hide();
    jQuery('#' + formid + ' .spinner').removeClass('hide');
    $(document).find('.frm-action button').attr('disabled', 'disabled');
    var data = new FormData($(document).find("#" + formid)[0]);
    jQuery.ajax({
        type: "POST",
        url: base_url + "templates/save",
        data: data,
        async: true,
        contentType: false,
        processData: false,
        success: function (data) {
            $(document).find('#btn_edit_template').removeAttr('disabled');
            var result = data.split("^");
            if (result[0] == "0") {
                $(".userMessageDiv").html("");
                $(".userMessageDiv").html(result[1]);
                $(".userMessageDiv").show();
                jQuery('#btn_edit_template .spinner').addClass('hide');
                $(document).find('#btn_edit_template').removeAttr('disabled');
            } else {
                var msg_txt = 'Template saved successfully.';
                if (result[0] == "2") {
                    msg_txt += 'Template image could not be saved.';
                }
                var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>x</span><span class="sr-only">Close</span></button>' + msg_txt + '</div>';
                $(".userMessageDiv").html(msg);
                $("html, body").animate({scrollTop: 0}, "slow");
                $(".userMessageDiv").show();
                jQuery('#btn_edit_template .spinner').addClass('hide');
                setTimeout(function () {
                    $(".userMessageDiv").hide();
                    $(document).find('#btn_edit_template').removeAttr('disabled');
                    window.location.href = base_url + 'templates'
                }, 2000);
            }
        }
    });
}

