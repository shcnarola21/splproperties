$(document).ready(function () {
    var primary = document.querySelector('.switchery-primary');
    var switchery = new Switchery(primary, {color: '#2196F3'});

    $('.daterange-single').daterangepicker({
        singleDatePicker: true,
        minDate: new Date()
    });

    $("#anytime-month-day").AnyTime_picker({
        format: "%M %d"
    });

    jQuery(document).on('click', "#btn_view_history", function (event) {
        $(document).find(".modal_div").load(base_url + 'tax_setting/load_package_history', function () {
            $(document).find('#modal_manage_package_history').modal('show');
            get_package_history();
            datatable_select2();
        });
        event.preventDefault();
    });

    function get_package_history() {
        $(document).find('#dt_list_package_history').dataTable({
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
            order: [[0, "desc"]],
            ajax: base_url + 'tax_setting/get_package_history',
            columns: [
                {
                    data: "id",
                    visible: true,
                    sortable: false
                },
                {
                    data: "package_name",
                    visible: true,
                },
                {
                    data: "h_date",
                    "bVisible": false
                },
                {
                    data: "percentage",
                    visible: true,
                    render: function (data, type, full, meta) {
                        return full.percentage + '%';
                    }
                },
                {
                    data: "org_price",
                    visible: true,
                },
                {
                    data: "new_price",
                    visible: true,
                },
                {
                    data: "created",
                    visible: true,
                },
            ],
            "drawCallback": function (settings) {
                var api = this.api();
                var rows = api.rows({page: 'current'}).nodes();
                var last = null;

                api.column(2, {page: 'current'}).data().each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                                '<tr class="group"><td colspan="6">' + group + '</td></tr>'
                                );

                        last = group;
                    }
                });
            },
        });

    }
});
jQuery(document).on('click', "#auto_send_yearly_tax_receipt", function (event) {
    if ($(this).is(':checked')) {
        $(document).find('.date_div').show(200);
    } else {
        $(document).find('.date_div').hide(200);
    }
});

jQuery(document).on('change', "#select_date", function (event) {
    if ($(this).val() != '') {
        if ($(this).val() == 'specific_date') {
            $(document).find('.auto_increase_date_div').show(200);
        } else {
            $(document).find('.auto_increase_date_div').hide(200);
        }
    } else {
        $(document).find('.auto_increase_date_div').hide(200);
    }
});

jQuery(document).on('submit', "#tax_setting_frm", function (event) {
    save_tax_settings('tax_setting_frm');
    event.preventDefault();
});

jQuery(document).on('submit', "#auto_increase_frm", function (event) {
    save_auto_increase('auto_increase_frm');
    event.preventDefault();
});

function save_tax_settings(formid) {
    var data = jQuery("#" + formid).serialize();
    $(".userMessageDiv").hide();
    jQuery('#' + formid + ' .spinner').removeClass('hide');
    $(document).find('#' + formid + ' .frm-action button').attr('disabled', 'disabled');
    $.post(base_url + "tax_setting/save", data,
            function (data) {
                $(document).find('#' + formid + ' .frm-action button').removeAttr('disabled');
                jQuery('#' + formid + ' .spinner').addClass('hide');
                var result = data.split("^");
                if (result[0] == "0") {
                    $(".userMessageDiv").html("");
                    $(".userMessageDiv").html(result[1]);
                    $(".userMessageDiv").show();
                    jQuery('.save_canned_response_btn .spinner').addClass('hide');
                    $(document).find('.btn_tax_setting').removeAttr('disabled');
                } else {
                    var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>x</span><span class="sr-only">Close</span></button>Tax Setting saved successfully.</div>';
                    $(".userMessageDiv").html(msg);
                    $(".userMessageDiv").show();
                    $("html, body").animate({scrollTop: 0}, "slow");
                    jQuery('.btn_tax_setting .spinner').addClass('hide');
                    setTimeout(function () {
                        $(".userMessageDiv").hide();
                        $(document).find('.btn_tax_setting').removeAttr('disabled');
                    }, 2000);
                }

            });
}

function save_auto_increase(formid) {
    var data = jQuery("#" + formid).serialize();
    $(".auto_increase_msg_div").hide();
    jQuery('#' + formid + ' .spinner').removeClass('hide');
    $(document).find('#' + formid + ' .frm-action button').attr('disabled', 'disabled');
    $.post(base_url + "tax_setting/save_auto_increase", data,
            function (data) {
                $(document).find('#' + formid + ' .frm-action button').removeAttr('disabled');
                jQuery('#' + formid + ' .spinner').addClass('hide');
                var result = data.split("^");
                if (result[0] == "0") {
                    $(".auto_increase_msg_div").html("");
                    $(".auto_increase_msg_div").html(result[1]);
                    $(".auto_increase_msg_div").show();
                    jQuery('.btn_auto_increase .spinner').addClass('hide');
                    $(document).find('.btn_tax_setting').removeAttr('disabled');
                } else {
                    var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>x</span><span class="sr-only">Close</span></button>Yearly auto increase package prices saved successfully.</div>';
                    $(".auto_increase_msg_div").html(msg);
                    $(".auto_increase_msg_div").show();
                    $("html, body").animate({scrollTop: 0}, "slow");
                    jQuery('.btn_auto_increase .spinner').addClass('hide');
                    setTimeout(function () {
                        $(".auto_increase_msg_div").hide();
                        $(document).find('.btn_auto_increase').removeAttr('disabled');
                        location.reload();
                    }, 2000);
                }

            });
}

