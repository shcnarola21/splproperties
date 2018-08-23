
//Customers lisitn g
if ($('#customers_dt_table')) {
    $('#customers_dt_table').dataTable({
        "sAjaxSource": base_url + 'customers/get',
        autoWidth: false,
        processing: true,
        serverSide: true,
        language: {
            search: '<span>Search:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
            emptyTable: 'No customer available.'
        },
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        order: [[0, "asc"]],
//        ajax: base_url + 'customers/get',
        columns: [
            {
                data: "cid",
                width: "8%",
                visible: true,
                sortable: false
            },
            {
                data: "name",
                width: "20%",
                visible: true,
                "render": function (data, type, full, meta) {
                    var popup_content = "<div><strong>Rating : </strong><input type='hidden'  class='rating' data-readonly value='" + full.rating + "' data-filled='icon-star-full2 symbol-filled' data-empty='icon-star-empty3 symbol-empty'></div>"
                            + "<div><strong>Name : </strong>" + data + "</div>"
                            + "<div><strong>Email : </strong>" + full.email + "</div>";
                    if (full.phone) {
                        popup_content += '<div><strong>Phone : </strong>' + full.phone + '</div>';
                    } else {
                        popup_content += '<div><strong>Phone : </strong>-</div>';
                    }
                    if (full.secondary_check == 'no') {
                        popup_content += '<hr/>';
                        popup_content += '<div><u><b>Secondary Contact</b></u></div>'
                                + '<div><strong>Secondary Name : </strong>' + full.secondary_name + '</div>';
                        if (full.secondary_phone) {
                            popup_content += '<div><strong>Secondary Phone : </strong>' + full.secondary_phone + '</div>';
                        } else {
                            popup_content += '<div><strong>Secondary Phone : </strong>-</div>';
                        }
//                    popup_content += '</div>';
                    }

                    return '<a href="' + base_url + 'customers/view/' + btoa(full.cid) + '" class="popovercls" data-popup="popover-custom" title="Info" data-trigger="hover" data-content="' + popup_content + '"><i class="icon-user"></i> ' + data + '</a>';
//                    return '<a href="' + base_url + 'customers/view/' + btoa(full.cid) + '" data-popup="tooltip" title="Right tooltip" data-placement="right"><i class="icon-user"></i> ' + data + '</a>';
                }
            },
            {
                "data": "address",
                width: "40%",
                "visible": true,
                "render": function (data, type, full, meta) {
                    var str = "";
                    if (full.property_id) {
                        if (full.p_address != "") {
                            str += full.p_address + ',&nbsp;&nbsp;';
                        }
                        if (full.p_city != "") {
                            str += full.p_city + ',&nbsp;&nbsp;';
                        }
                        if (full.p_state != "") {
                            str += full.p_state + ',&nbsp;&nbsp;';
                        }
                        if (full.p_zip_code != "") {
                            str += full.p_zip_code + ',&nbsp;&nbsp;';
                        }
                        if (full.p_country != "") {
                            str += full.p_country;
                        }
                        if (full.unit != "") {
                            str += '&nbsp;&nbsp;&nbsp;<b>Unit</b>: ' + full.unit;
                        }
                    } else {
                        if (full.address != "") {
                            str += full.address + ',&nbsp;&nbsp;';
                        }
                        if (full.city != "") {
                            str += full.city + ',&nbsp;&nbsp;';
                        }
                        if (full.state != "") {
                            str += full.state + ',&nbsp;&nbsp;';
                        }
                        if (full.zip_code != "") {
                            str += full.zip_code + ',&nbsp;&nbsp;';
                        }
                        if (full.country != "") {
                            str += full.country;
                        }
                    }

                    return str;
                }
            },
            {
                "data": "start_date",
                width: "14%",
                "visible": true,
                "render": function (data, type, full, meta) {
                    var start_date = "";
                    if (full.start_date != "0000-00-00 00:00:00") {
                        start_date = full.start_date;
                    }
                    return start_date;
                }
            },
            {
                data: "status",
                width: "8%",
                visible: true,
                render: function (data, type, full, meta) {
                    var str = '';
                    var terminated_popup_content = ''
                    if (full.status != '' && full.status != null) {
                        if (full.status == "active") {
                            str = '<label class="label bg-success">Active</label>';
                        } else if (full.status == "suspended") {
                            str = '<label class="label bg-warning">Suspended</label>';
                        } else if (full.status == "terminated") {
                            var terminated_popup_content = '<div><strong>Termination Date: </strong><span>' + full.last_terminated_on + '</span></div>';
                            str = '<label class="label bg-danger popovercls" data-popup="popover-custom-terminated" title="Termination Info" data-trigger="hover" data-placement="top" data-content="' + terminated_popup_content + '">Terminated</label>';
                        } else {
                            str = '<label class="label bg-danger">InActive</label>';
                        }
                    }
                    return str;
                }
            },
            {
                data: "action",
                width: "8%",
                render: function (data, type, full, meta) {
                    var str = '';
                    str += '<ul class="icons-list pull-left"><li class="dropdown">';
                    str += '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a>';
                    str += '<ul class="dropdown-menu dropdown-menu-right">';
//                    str += '<li><a href="' + base_url + 'customers/edit/' + btoa(full.id) + '"><i class="icon-pencil7"></i> Edit</a></li>';
                    str += '<li><a href="' + base_url + 'customers/view/' + btoa(full.cid) + '"><i class=" icon-zoomin3"></i> View</a></li>';
//                    str += '<li><a href="' + base_url + 'customers/delete/' + btoa(full.id) + '" class="" onclick="return confirm_alert(this)"><i class="icon-trash"></i> Delete</a></li>';
                    str += '</ul></li></ul>';
                    str += '</ul>';
                    ///str += '<a class="btn btn-primary pull-left" style="margin-left: 20px;"  href="' + base_url + 'users/autologin/' + btoa(full.id) + '">Auto Login</a>'
                    return str;
                },
                sortable: false,
            },
        ],
        "fnDrawCallback": function (oSettings) {
            jQuery('.checkboxes').each(function (index) {
                if (!jQuery(this).parent().parent().hasClass("checker")) {
                    jQuery(this).uniform();
                }
            });
            $('[data-popup=popover-custom]').popover({
                html: true,
                template: '<div class="popover border-primary-600"><div class="arrow"></div><h3 class="popover-title bg-primary-600"></h3><div class="popover-content"></div></div>',
                trigger: 'hover'

            }).on('shown.bs.popover', function () {
                $('body .popover .rating').rating();
            });
            $('[data-popup=popover-custom-terminated]').popover({
                html: true,
                template: '<div class="popover border-danger-600"><div class="arrow"></div><h3 class="popover-title bg-danger-600"></h3><div class="popover-content"></div></div>'});
        },
        'fnServerData': function (sSource, aoData, fnCallback)
        {
            var ischecked = $("#show_terminated").is(':checked');
            var ischeckedVal = 0;
            if (ischecked)
            {
                ischeckedVal = 1;
            }
            aoData.push({"name": "show_terminated", "value": ischeckedVal});
            $.ajax
                    ({
                        'dataType': 'json',
                        'url': sSource,
                        'data': aoData,
                        'success': fnCallback
                    });
        }
    });
    datatable_select2();
}
jQuery('#show_terminated').change(function () {
    $('#customers_dt_table').DataTable().ajax.reload(null, false);
});
select2_dropdown_with_search();
$('.daterange-single').daterangepicker({
    singleDatePicker: true
});
if ($('.tokenfield').length > 0) {
    $('.tokenfield').tokenfield();
}

//Choose Package change event
jQuery(document).on('change', "#customer_package", function (event) {
    $(".selected_service_package").val('');
    var current_frm_id = $(this).closest('form').attr('id');
    var package_id = $(this).val();
    $(".package_info").load(base_url + 'customers/get_package_info', {package: btoa(package_id)}, function () {

    });
    var edit_order_id = "";
    var order_id = $("#" + current_frm_id + " input[name='order_id']").data('order_id');
    if (order_id != "" && typeof order_id != 'undefined') {
        edit_order_id = order_id;
    }
    if (package_id != "" && package_id > 0) {
        check_package_service(package_id);
    } else {
        $(".addon-wrapper").hide();
        $(".package-addon-wrapper").hide();
        $(document).find(".hardware_div").html('');
    }
    event.preventDefault();
});
function check_package_service(package_id) {
    var current_frm_id = $(document).find('form:visible').attr('id');
    if (current_frm_id == 'customer_package_frm') {
        $(document).find('#' + current_frm_id + ' .loader_div').removeClass('hide');
    }
    $.ajax({
        url: base_url + 'customers/check_package_service/' + package_id,
        type: "get",
        success: function (data)
        {
            var json = $.parseJSON(data);
            $(".package-addon-wrapper").show();
//            $(document).find('#frm_customer_add .choose_addon_cls option').length;
            if (current_frm_id == 'frm_customer_add' || current_frm_id == 'customer_package_frm') {
                json.status = true;
            }
            if (json.status)
            {

                $('#' + current_frm_id + " .li_package a").trigger('click');
                $('#' + current_frm_id + " .hardware_div").hide();
                //  var length = $('#' + current_frm_id + ' .choose_addon_cls option').length;

                $('#' + current_frm_id + " .li_addon").hide();
                console.log("in here", '#' + current_frm_id + " .customer_addon_cls");
                $('#' + current_frm_id + " .customer_addon_cls").hide();
                if (json.service_addon != '' && json.service_addon != null && (json.service_addon).length > 0) {
                    var option = '';
                    $.each(json.service_addon, function ()
                    {
                        var service_class = (this.services == 'VPN') ? 'text-success' : '';
                        option += '<option class="' + service_class + '" value="' + this.id + '">' + this.name + ' - $' + this.price + '</option>';
                    });
                    $('#' + current_frm_id + " .service_addon_cls").html(option);
                    if (current_frm_id == 'customer_package_frm') {
                        $(document).find('#' + current_frm_id + ' .loader_div').addClass('hide');
                    }
                    select2_dropdown();
                    $('#' + current_frm_id + ' #sp_addon_id').val('');
                    $('#' + current_frm_id + " .li_addon").show();
                    $('#' + current_frm_id + " .customer_addon_cls").show();
                }

            } else {
                if (current_frm_id == 'customer_package_frm') {
                    $(document).find('#' + current_frm_id + ' .loader_div').addClass('hide');
                }
                $('#' + current_frm_id + " .li_addon").hide();
                $('#' + current_frm_id + " .customer_addon_cls").hide();
            }

        }
    });
}

jQuery(document).on('change', ".property_address", function (event) {
    var current_frm_id = $(this).closest('form').attr('id');
    var property_id = $(this).val();
    if (property_id != 'other') {
        $(".input_address_div").hide();
        $(".property_unit_select").show();
        jQuery.ajax({
            type: "POST",
            url: base_url + "customers/get_property_units",
            data: {property_id: property_id},
            success: function (data) {
                $(document).find('.property_unit').html(data);
            }
        });
    } else if (property_id == 'other') {
        $(".input_address_div").show();
        $(".property_unit_select").hide();
    }
    event.preventDefault();
});
jQuery(document).on('submit', "#frm_customer_add", function (event) {
    create_customer('frm_customer_add');
    event.preventDefault();
});
function create_customer(formid) {
    $(document).find('.form-wrapper .icon-info22').remove();
    $(".userMessageDiv").html("");
    jQuery('#' + formid + ' .spinner').removeClass('hide');
    var data = jQuery("#" + formid).serialize();
//    jQuery('#loading').show();
    $(document).find('.form-actions button').attr('disabled', 'disabled');
    jQuery.ajax({
        type: "POST",
        url: base_url + "customers/add_customer",
        data: data,
        success: function (data) {
            $(document).find('.form-actions button').removeAttr('disabled');
            var json = jQuery.parseJSON(data);
            if (!json.status) {
//                console.log("in if", formid);
                jQuery('#' + formid + ' .spinner').addClass('hide');
                high_light_tab(json.tabs);
                $('.userMessageDiv').html("");
                $('.userMessageDiv').html(json.msg);
                $('.userMessageDiv').show();
            } else {
//                console.log("in else");
                var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>x</span><span class="sr-only">Close</span></button>Customer saved successfully.</div>';
                $('.userMessageDiv').html(msg);
                $('.userMessageDiv').show();
                jQuery('#' + formid + ' .spinner').addClass('hide');
                setTimeout(function () {
                    $('.userMessageDiv').hide();
                    window.location.href = base_url + 'customers';
                }, 1000);
            }

        }
    });
}

jQuery(document).on('change', "#customer_account_status", function (event) {
    var selected_customer_status = jQuery("#customer_account_status").val();
    if (selected_customer_status == 'terminated') {
        jQuery(".terminate_status_div").show();
    } else {
        jQuery(".terminate_status_div").hide();
    }
});
jQuery(document).on('change', "#terminate_info", function (event) {
//    jQuery("#terminate_info").change(function () {
    var selected_customer_status = jQuery("#terminate_info option:selected").val();
    if (selected_customer_status == 'specific') {
        jQuery(".hide_terminate_specific_date").show();
    } else if (selected_customer_status == 'now' || selected_customer_status == 'on_renewal') {
        jQuery(".hide_terminate_specific_date").hide();
    } else {
        jQuery("#terminate_specific_date").val('');
        jQuery(".hide_terminate_specific_date").hide();
    }
});
jQuery(document).on('submit', "#customer_edit_frm", function (event) {
    edit_customer('customer_edit_frm');
    event.preventDefault();
});
function edit_customer(formid)
{
    var data = jQuery("#" + formid).serialize();
    $(document).find("#" + formid + ' .btn_edit_customer').attr('disabled', 'disabled');
    $(document).find("#" + formid + ' .btn_edit_customer .spinner').removeClass('hide');
    $.post(base_url + "customers/save", data,
            function (data) {
                var result = data.split("^");
                $(document).find("#" + formid + ' .btn_edit_customer').removeAttr('disabled');
                if (result[0] == "0") {
                    $(document).find("#" + formid + ' .btn_edit_customer .spinner').addClass('hide');
                    $(".userMessageDiv").html("");
                    $("html, body").animate({scrollTop: 0}, "slow");
                    $(".edit_customer_wrapper").animate({scrollTop: 0}, "slow");
                    $(".userMessageDiv").html(result[1]);
                    $(".userMessageDiv").show();
                } else {
                    var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button class="close" data-dismiss="alert"><span>x</span></button>Customer saved successfully.</div>';
                    $(".userMessageDiv").html(msg);
                    $(".userMessageDiv").show();
                    $(".edit_customer_wrapper").animate({scrollTop: 0}, "slow");
                    $(document).find("#" + formid + ' .btn_edit_customer .spinner').addClass('hide');
                    setTimeout(function () {
                        $('#edit_customer_modal').modal('hide');
                        $(".userMessageDiv").hide();
                        //jQuery("#customer_add_frm")[0].reset();

                        $(".customer_info_editable").html('');
                        location.reload();
                    }, 2000);
                }
            });
}
//Customer module
//get country seleted value
var country_list = $('.country_list option:selected').val();
set_state_section(country_list);
var billing_country = $('#billing_country option:selected').val();
set_billing_state_section(billing_country);
//function for the update payment type for the customer - view - accountinfo tab
jQuery(document).on('change', ".country_list", function (event) {
    var country = $('.country_list option:selected').val();
    $(".is_visible .state_txt").val('');
    set_state_section(country);
    event.preventDefault();
});
//function for the update payment type for the customer - view - accountinfo tab

jQuery(document).on('change', ".billing_country_list", function (event) {
    var billing_country = $('.is_visible .billing_country_list option:selected').val();
    $(".is_visible .billing_state_txt_cls").val('');
    set_billing_state_section(billing_country);
    event.preventDefault();
});
jQuery(document).on('click', ".customer_billing_info_cls", function (event) {

    if ($(this).is(':checked'))
    {
        $(".billing_info_cls").hide();
    } else
    {
        $(".billing_info_cls").show();
    }
});
jQuery(document).on('click', ".customer_secondary_info_cls", function (event) {

    if ($(this).is(':checked'))
    {
        $(this).val('yes');
        $(".secondary_info_cls").hide();
    } else
    {
        $(this).val('no');
        $(".secondary_info_cls").show();
    }
});
$("#frm_customer_add #billing_info").hide();
function set_state_section(country)
{
    if (country == "Canada") {
        $(".state_opt").show();
        $(".state_opt").addClass('select-search');
        select2_dropdown_with_search();
        $(".state_opt").attr("name", "state");
        $('.state_opt').next('span').show();
        $(".state_txt").hide();
        $(".state_txt").attr("name", "");
    } else {
        $(".state_opt").hide();
        $(".state_opt").removeClass('select-search');
        $(".state_opt").attr("name", "");
        $('.state_opt').next('span').hide();
        $(".state_txt").show();
        $(".state_txt").attr("name", "state");
    }
}

function set_billing_state_section(country)
{
    if (country == "Canada") {
        $(".billing_state_opt_cls").show();
        $(".billing_state_opt_cls").attr("name", "billing_state");
        $(".billing_state_txt_cls").hide();
        $(".billing_state_txt_cls").attr("name", "");
    } else {
        $(".billing_state_opt_cls").hide();
        $(".billing_state_opt_cls").attr("name", "");
        $(".billing_state_txt_cls").show();
        $(".billing_state_txt_cls").attr("name", "billing_state");
    }
}
//For view customer module

//Start : auto refresh tabs while clicking on tab Customer - view  
function get_customer_tab_detail(tab, log_tab) {
    $(".userMessageDiv").html('');
    jQuery.ajax({
        type: "POST",
        data: {'tab': tab},
        url: base_url + 'customers/get_tab_view/' + pay_customer,
        success: function (data) {
            switch (tab) {
                case 'AccountInfo':
                    $("#account_tab").html(data);
                    styled_checkbox();
                    break;
                case 'Packages':
                    $("#package_tab").html(data);
                    styled_checkbox();
                    select2_dropdown();
                    select2_dropdown_with_search();
                    break;
                case 'CardInfo':
                    $("#cardinfo").html(data);
                    break;
                case 'Notes':
                    $('#customer_notes_dttable').DataTable().ajax.reload(null, false);
                    break;
                case 'Credit':
                    $('#customer_credits_dttable').DataTable().ajax.reload(null, false);
                    get_total_customer_credit();
                    get_customer_lastmonth_credit();
                    break;
                case 'Eviction':
                    $('#customer_evictions_dttable').DataTable().ajax.reload(null, false);
                    break;
                case 'Contracts':
                    $('#customer_contracts_dttable').DataTable().ajax.reload(null, false);
                    break;
            }

            return false;
        }
    });
}
//End : auto refresh tabs while clicking on tab Customer - view  
jQuery(document).on('click', ".secondary_info_chk", function (event) {
    var ch_ckd = false;
    if ($(this).is(':checked'))
    {
        ch_ckd = true;
        jQuery(".secondary_section").fadeOut();
        jQuery(".edit_secondary_info").fadeOut();
    } else
    {
        jQuery(".secondary_section").fadeIn();
        jQuery(".edit_secondary_info").fadeIn();
    }
    jQuery.ajax({
        type: "POST",
        url: base_url + "customers/save_secondary_check/" + pay_customer,
        data: {"secondary_check": ch_ckd},
        success: function (data) {
        }
    });
});
jQuery(document).on('click', ".billing_info_chk", function (event) {
    var ch_ckd = false;
    if ($(this).is(':checked'))
    {
        ch_ckd = true;
        jQuery(".bill_section").fadeOut();
        jQuery(".edit_billing_info").fadeOut();
    } else
    {
        jQuery(".bill_section").fadeIn();
        jQuery(".edit_billing_info").fadeIn();
    }
    jQuery.ajax({
        type: "POST",
        url: base_url + "customers/save_billing_check/" + pay_customer,
        data: {"billing_check": ch_ckd},
        success: function (data) {
        }
    });
});
jQuery(document).on('click', "#call_security_model_ajax", function (event) {
    if (pay_customer) {
        $.ajax({
            url: base_url + "customers/get_call_security/" + pay_customer + '/true',
            type: "post",
            success: function (data)
            {
                if (data) {
                    var result = JSON.parse(data);
                    if (result.length > 0) {
                        $(document).find('#call_secuirty_modal').find('input[name="security_question"]').val(result[0]['security_question']);
                        $(document).find('#call_secuirty_modal').find('input[name="security_answer"]').val(result[0]['security_answer']);
                        $(document).find('#call_secuirty_modal').find('input[name="date_of_birth"]').val(result[0]['date_of_birth']);
                        if (result[0]['date_of_birth']) {

                        }
                        $('.daterange-single').daterangepicker({
                            singleDatePicker: true,
                            parentEl: '#call_secuirty_modal',
                            endDate: new Date()
                        });
                    } else {
                        $('.daterange-single').daterangepicker({
                            singleDatePicker: true,
                            parentEl: '#call_secuirty_modal',
                            maxDate: new Date()
                        });
                    }
                }
            }
        });
    }
    $('#call_secuirty_modal').modal('show');
});
// jQuery(document).on('click', ".btn_save_call_security", function (event) {
jQuery(document).on('submit', "#call_security_frm", function (event) {
    save_call_security();
    event.preventDefault();
});
function save_call_security() {
    var data = jQuery("#call_security_frm").serialize();
    $(document).find('.btn_save_call_security').attr('disabled', 'disabled');
    $(document).find('.btn_save_call_security .spinner').removeClass('hide');
    $(document).find('.cs_loading').show();
    jQuery.ajax({
        type: "POST",
        url: base_url + "customers/save_call_security/" + pay_customer,
        data: data,
        success: function (data) {
            $(document).find('.cs_loading').hide();
            var result = data.split("^");
            $(document).find('.btn_save_call_security').removeAttr('disabled');
            if (result[0] == "0") {
                $(document).find('.btn_save_call_security .spinner').addClass('hide');
                $(document).find("#CSsuccessMessage").html("");
                $(document).find("#CSsuccessMessage").html(result[1]);
                $(document).find("#CSsuccessMessage").show();
            } else {
                var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button class="close" data-dismiss="alert"></button>Call Security updated successfully.</div>';
                $(document).find("#CSsuccessMessage").html(msg);
                $(document).find("#CSsuccessMessage").show();
                get_log_data('account_info');
                setTimeout(function () {
                    $(document).find("#CSsuccessMessage").hide();
                    $(document).find('.btn_save_call_security .spinner').addClass('hide');
                    $(document).find(".call_secuirty_modal ").modal('hide');
                    get_customer_tab_detail('AccountInfo', 'account_tab');
                }, 4000);
            }

        }
    });
    return false;
}

jQuery(document).on('change', "#customerview_package", function () {
    var selected_package = $(this).val();
    check_package_service(selected_package);
    $(".package_info").load(base_url + 'customers/get_package_info', {package: btoa(selected_package)}, function () {

    });
});
//customer edit
jQuery(document).on('click', '#customer_edit', function (e) {
//    data: {'id': $(this).attr('data-info')},
    $(".customer_info_editable").load(base_url + 'customers/edit', {'id': $(this).attr('data-info')}, function () {
        tag_it();
        select2_dropdown();
        styled_checkbox();
        select2_dropdown_with_search();
        $('.rating').rating();
        $('#edit_customer_modal').modal('show');
    });
    e.preventDefault();
});
// Send email to customer
jQuery(document).on('click', '#send_email', function (e) {
//    data: {'id': $(this).attr('data-info')},
    $(".customer_email_editable").load(base_url + 'customers/send_email_view', {'id': $(this).attr('data-info')}, function () {
        tag_it();
        select2_dropdown();
        CKEDITOR.replace('message', {
            height: 'auto',
            extraPlugins: 'forms'
        });
        $('#email_customer_modal').modal('show');
//        setTimeout(function () {
//            ck_editorbyclass('message')
//        }, 200);
    });
    e.preventDefault();
});
jQuery(document).on('click', ".btn_send_email", function (event) {
    var formid = 'customer_send_email_frm';
    for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }
    var data = $(document).find("#" + formid).serialize();
    $("#" + formid + " .userMessageDiv").hide();
    jQuery('#' + formid + ' .spinner').removeClass('hide');
    $(document).find('.btn_send_email .frm-action button').attr('disabled', 'disabled');
    $.post(base_url + "customers/send_customer_to_email", data,
            function (data) {
                $(document).find('.btn_send_email .frm-action button').removeAttr('disabled');
                jQuery('#' + formid + ' .spinner').addClass('hide');
                var result = data.split("^");
                if (result[0] == "0") {
                    $("#" + formid + " .userMessageDiv").html("");
                    $("#" + formid + " .userMessageDiv").html(result[1]);
                    $("#" + formid + " .userMessageDiv").show();
                    $("#" + formid + " .spinner").addClass('hide');
                } else {
                    var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>x</span><span class="sr-only">Close</span></button>Email sent to customer successfully.</div>';
                    $("#" + formid + " .userMessageDiv").html(msg);
                    $("#" + formid + " .userMessageDiv").show();
                    $("html, body").animate({scrollTop: 0}, "slow");
                    $("#" + formid + " .spinner").addClass('hide');
                    setTimeout(function () {
                        $('#email_customer_modal').modal('hide');
                        $('#email_customer_modal').on('hidden.bs.modal', function () {
                            $('#email_customer_modal').parent('div').html('');
                        });
                        $("#" + formid + " .userMessageDiv").hide();
                        get_customer_tab_detail('AccountInfo', 'account_tab');
                    }, 2000);
                }

            });
    event.preventDefault();
});
jQuery(document).on('change', "#canned_response_id", function () {
    var canned_response = $(this).val();
    jQuery.ajax({
        type: "POST",
        url: base_url + "customers/get_canned_response_info",
        data: {id: canned_response},
        success: function (data) {
            var data = JSON.parse(data);
            if (data.canned_response) {
                $(document).find('#subject').val(data.canned_response.subject);
                $(document).find('#message').html(data.canned_response.subject);
                CKEDITOR.instances['message'].setData(data.canned_response.body);
            } else {
                $(document).find('#subject').val('');
                $(document).find('#message').html('');
                CKEDITOR.instances['message'].setData('');
            }
        }
    });
});
jQuery(document).on('click', '.edit_billing_info', function (e) {
    $(".customer_info_editable").load(base_url + 'customers/edit', {'id': $(this).attr('data-info'), 'edit': 'billing'}, function () {
        tag_it();
        select2_dropdown();
//        $(document).find('#customer_country').chosen();
        $('#edit_customer_modal').modal('show');
    });
    e.preventDefault();
});
jQuery(document).on('click', '#customer_accountinfo_edit', function (e) {
    $(".customer_info_editable").load(base_url + 'customers/edit', {'id': $(this).attr('data-info'), 'edit': 'accountinfo'}, function () {
        select2_dropdown();
        $('.daterange-single').daterangepicker({
            singleDatePicker: true,
            parentEl: '#edit_customer_modal',
        });
        $('#edit_customer_modal').modal('show');
    });
    e.preventDefault();
});
//Package Tab changes

//update customer Packages START

$(document).on('click', '.add_addon_tab', function (e) {
    $(document).find('.no_rentaladdon').addClass('hide');
    $(document).find('.rental_addon_li').removeClass('hide');
    if ($(document).find('.rental_addon_li li:last-child a').length <= 0) {
        var next_index = 1;
        var next_active = 'active';
    } else {
        var next_index = parseInt($(document).find('.rental_addon_li li:last-child a').data('rentaladdon').match(/\d+/)) + 1;
        var next_active = '';
    }
    if (next_index) {
        $(document).find('.rental_addon_li').append('<li class="li_rentaladdon_' + next_index + ' ' + next_active + '"><a class="custom_tab" href="#rentaladdon_' + next_index + '" data-toggle="tab" data-rentaladdon="#rentaladdon_' + next_index + '">Addon ' + next_index + ' <i class="text-danger icon-minus-circle2 remove_rentaladdon"></i></a></li>');
        $(document).find('.rental_addon_tab_content').append('<div class="tab-pane ' + next_active + '" id="rentaladdon_' + next_index + '" />');
        $('#rentaladdon_' + next_index).load(base_url + '/customers/manage_rentaladdon_tab', {index: next_index}, function () {
        });
    }
    e.preventDefault();
});

$(document).on('click', '.remove_rentaladdon', function (e) {
    var current_this = e.target;
    var rental_addon = $(current_this).data('addon');
    if (rental_addon) {
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
                        delete_rentaladdon(rental_addon);
                        remove_addon_div(current_this);
                        return true;
                    } else {
                        return false;
                    }
                });
    } else {
        remove_addon_div(current_this);
    }
    e.preventDefault();
});
function remove_addon_div(e) {
    var tab_remove = $(e).parent('a').attr('href');
    var tab_remove_li = $(e).parents('li');
    if (tab_remove) {
        if (tab_remove_li.hasClass('active')) {
            if ($(document).find('.rental_addon_li li:first-child a').attr('href') == tab_remove) {
                tab_remove_li.next().addClass('active');
                $(document).find(tab_remove_li.next().find('a').attr('href')).addClass('active');
            } else {
                tab_remove_li.prev().addClass('active');
                $(document).find(tab_remove_li.prev().find('a').attr('href')).addClass('active');
            }
        }
        $(document).find(tab_remove).remove();
        $(e).parents('li').remove();
        if ($(document).find('.rental_addon_li li:last-child a').length <= 0) {
            $(document).find('.no_rentaladdon').removeClass('hide');
            $(document).find('.rental_addon_li').addClass('hide');
        } else {
            $(document).find('.no_rentaladdon').addClass('hide');
            $(document).find('.rental_addon_li').removeClass('hide');
        }
    }
}

//jQuery(document).on('click', ".delete_camera", function (event) {
//    var camera_id = $(this).attr('data-camera');
//    
//    return false;
//});
function delete_rentaladdon(rentaladdon_id) {
    $.ajax({
        url: base_url + 'customers/delete_rentaladdon',
        type: "post",
        data: {'id': rentaladdon_id},
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
                }, 1000);
            }
        }
    });
}
jQuery(document).on('submit', "#customer_package_frm", function (event) {
    update_packages('customer_package_frm');
    event.preventDefault();
});
function update_packages(formid)
{
    var data = jQuery("#" + formid).serialize();
    $("#" + formid + " .userMessageDiv").hide();
    $(document).find('#' + formid + ' .form-actions button').attr('disabled', 'disabled');
    $(document).find('#' + formid + ' .form-actions .spinner').removeClass('hide');
    $.post(base_url + "customers/packages_save/" + pay_customer, data,
            function (data) {
                jQuery('#' + formid + ' .form-actions button').removeAttr('disabled');
                var result = data.split("^");
                var json = jQuery.parseJSON(data);
                if (!json.status) {
                    $(document).find('#' + formid + ' .form-actions .spinner').addClass('hide');
                    if (json.tabs) {
                        high_light_tab(json.tabs);
                    }
                    $("#" + formid + " .userMessageDiv").html("");
                    $("#" + formid + " .userMessageDiv").html(json.msg);
                    $("#" + formid + " .userMessageDiv").show();
                } else {
                    var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button class="close" data-dismiss="alert"><span>x</span></button>Packages updated successfully.</div>';
                    $("#" + formid + " .userMessageDiv").html(msg);
                    $("#" + formid + " .userMessageDiv").show();
                    $(document).find('#' + formid + ' .form-actions .spinner').addClass('hide');
//                    jQuery("#" + formid)[0].reset();
                    setTimeout(function () {
                        $(document).find("#" + formid + " .userMessageDiv").hide();
//                        window.location.href = base_url + "customers/view/" + pay_customer + '?t=package';
                        get_customer_tab_detail('Packages');
                    }, 4000);
                }
            });
}
//update customer Packages END


jQuery(document).on('click', ".lock_unlock_card", function (event) {
    $("#creditcard_view_password_frm .userMessageDiv").html('');
    $("#creditcard_view_password").val('');
    $("#creditcard_view_password").data('id', '');
    $("#creditcard_view_password").data('type', '');
    $("#creditcard_view_password").data('id', $(this).data('id'));
    $("#creditcard_view_password").data('type', $(this).data('type'));
    $(".creditcard_view_password_model").modal('show');
    setTimeout(function () {
        $(document).find('.creditcard_view_password_model #creditcard_view_password').focusTextToEnd();
    }, 100);
});
setTimeout(function () {
    $(document).find('#edit_device_limit_frm .txt_dl:visible').focusTextToEnd();
}, 100);
jQuery(document).on('click', ".btn_creditcard", function (e) {
    $(".loading_notice_timer").show();
    $("#creditcard_view_password_frm .btn_creditcard .spinner").removeClass('hide');
    $("#creditcard_view_password_frm .userMessageDiv").html('');
    var id = $('#creditcard_view_password').data('id');
    var type = $('#creditcard_view_password').data('type');
    $(document).find('#creditcard_view_password_frm .form-actions button').attr('disabled', 'disabled');
    $.ajax({
        url: base_url + 'customers/get_creditcard_number',
        type: "post",
        data: {'password': $('#creditcard_view_password').val(), 'type': type, 'id': btoa($('#creditcard_view_password').data('id'))},
        success: function (response) {
            $("#creditcard_view_password_frm .btn_creditcard .spinner").addClass('hide');
            if (response == "0") {
                $(document).find('#creditcard_view_password_frm .form-actions button').removeAttr('disabled');
                var msg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><span>x</span></button>Password verification failed.</div>';
                $("#creditcard_view_password_frm .userMessageDiv").html(msg);
                $("#creditcard_view_password_frm .userMessageDiv").show();
            } else {
                if (type == "customer") {
                    $(".cc_" + id).val(response);
                }
                $('.lock_unlock_card[data-id = ' + id + ']').removeClass('icon-lock lock_unlock_card').addClass('icon-unlocked')
                $(".creditcard_view_password_model").modal('hide');
            }
        },
    });
    e.preventDefault();
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
//Notes tab
$('#customer_notes_dttable').dataTable({
    autoWidth: false,
    processing: true,
    serverSide: true,
    language: {
        search: '<span>Search:</span> _INPUT_',
        lengthMenu: '<span>Show:</span> _MENU_',
        paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
        emptyTable: 'No notes available.'
    },
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    order: [[0, "asc"]],
    ajax: base_url + 'customers/get_notes/' + pay_customer,
    columns: [
        {
            "data": "id",
            width: "5%",
            "visible": true
        },
        {
            "data": "created",
            width: "10%",
            "visible": true
        },
        {
            "data": "notes",
            width: "20%",
            "visible": true
        },
        {
            "data": "added_by_name",
            width: "10%",
            "visible": true,
        },
        {
            "visible": true,
            width: "8%",
            "sortable": false,
            render: function (data, type, full, meta) {
                var str = '<ul class="icons-list"><li class="dropdown">';
                str += '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a>';
                str += '<ul class="dropdown-menu dropdown-menu-right">';
                str += '<li><a href="javascript:void(0);" data-note=' + btoa(full.id) + ' class="note_edit"><i class="icon-pencil5"></i> Edit</a></li>';
                str += '<li><a href="javascript:void(0);" data-note=' + btoa(full.id) + ' class="note_delete"><i class="icon-trash"></i> Delete</a></li>';
                str += '</ul></li></ul>';
                str += '</ul>';
                return str;
            },
        }
    ],
});
//notes add event
jQuery(document).on('click', "#note_add", function (e) {
    $(".manage_notes").load(base_url + 'customers/load_manage_notes', {note_for: pay_customer}, function () {
        jQuery('#modal_manage_notes').modal('show');
    });
    e.preventDefault();
});
jQuery(document).on('click', ".note_edit", function (event) {
    var note_id = $(this).attr('data-note');
    $(".manage_notes").load(base_url + 'customers/load_manage_notes', {note: note_id, note_for: pay_customer}, function () {
        jQuery('#modal_manage_notes').modal('show');
    });
});
jQuery(document).on('click', ".note_delete", function (event) {
    var note_id = $(this).attr('data-note');
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
                    delete_note(note_id);
                    return true;
                } else {
                    return false;
                }
            });
    return false;
});
function delete_note(note_id) {
    $.ajax({
        url: base_url + 'customers/delete_note',
        type: "post",
        data: {'id': note_id},
        success: function (response) {
            if ($('#customer_notes_dttable').html()) {
                $('#customer_notes_dttable').DataTable().ajax.reload(null, false);
            }
            if ($('#notes_dttable').html()) {
                $('#notes_dttable').DataTable().ajax.reload(null, false);
            }
            swal("Deleted!", "Note deleted successfully.", "success");
            setTimeout(function () {
                $('.sa-button-container .confirm').trigger('click');
            }, 4000);
        }
    });
}

jQuery(document).on('submit', "#manage_note_frm", function (event) {
    add_note('manage_note_frm');
    event.preventDefault();
});
function add_note(formid)
{
    var data = jQuery("#" + formid).serialize();
    $("#" + formid + " .btn_notes .spinner").removeClass('hide');
    $(document).find("#" + formid + " .form-actions button").attr('disabled', 'disabled');
    jQuery.ajax({
        type: "POST",
        url: base_url + "notes/save_note",
        data: data,
        success: function (data) {
            var result = data.split("^");
            if (result[0] == "0") {
                $(document).find("#" + formid + " .form-actions button").removeAttr('disabled');
                $("#" + formid + " .btn_notes .spinner").addClass('hide');
                $("#" + formid + " .userMessageDiv").html("");
                $("#" + formid + " .userMessageDiv").html(result[1]);
                $("#" + formid + " .userMessageDiv").show();
            } else {
                $("#" + formid + " .btn_notes .spinner").addClass('hide');
                var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button class="close" data-dismiss="alert"></button>Note saved successfully. please wait while loading data in table.</div>';
                $("#" + formid + " .userMessageDiv").html(msg);
                $("#" + formid + " .userMessageDiv").show();
                setTimeout(function () {
                    if ($('#customer_notes_dttable').html()) {
                        $('#customer_notes_dttable').DataTable().ajax.reload(null, false);
                    }
                    if ($('#notes_dttable').html()) {
                        $('#notes_dttable').DataTable().ajax.reload(null, false);
                    }
                    $("#" + formid + " .userMessageDiv").hide();
                    jQuery('#modal_manage_notes').modal('hide');
                    remove_modal_backdrop();
                    remove_modal('modal_manage_notes');
                    $(document).find("#" + formid + " .form-actions button").removeAttr('disabled');
                    jQuery("#" + formid)[0].reset();
                }, 2000);
            }
        }
    });
}
//Notes tab
// Customer listing
$('#customer_credits_dttable').dataTable({
    autoWidth: false,
    processing: true,
    serverSide: true,
    language: {
        search: '<span>Search:</span> _INPUT_',
        lengthMenu: '<span>Show:</span> _MENU_',
        paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
        emptyTable: 'No notes available.'
    },
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    order: [[0, "asc"]],
    ajax: base_url + 'customers/get_credits/' + pay_customer,
    columns: [
        {
            "data": "id",
            width: "5%",
            "visible": true
        },
        {
            "data": "amount",
            width: "10%",
            "visible": true,
            render: function (data, type, full, meta) {
                var str = '$' + data;
                return str;
            },
        },
        {
            "data": "added_by_name",
            width: "10%",
            "visible": true,
        },
        {
            "data": "type",
            width: "8%",
            "visible": true,
            render: function (data, type, full, meta) {
                var str = '';
                if (data == "General") {
                    str = '<label class="label bg-success">' + data + '</label>';
                } else if (data == "Last Month") {
                    str = '<label class="label bg-slate">' + data + '</label>';
                } else if (data == "Damage(s)") {
                    str = '<label class="label bg-danger">' + data + '</label>';
                } else {
                    str = data;
                }
                return str;
            },
        },
        {
            "data": "month",
            "bVisible": false
        },
        {
            "data": "created",
            width: "12%",
            "visible": true,
        },
        {
            "data": "description",
            width: "20%",
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
                str += '<li><a href="javascript:void(0);" data-credit=' + btoa(full.id) + ' class="credit_delete"><i class="icon-trash"></i> Delete</a></li>';
                str += '</ul></li></ul>';
                str += '</ul>';
                return str;
            },
        }
    ],
    "drawCallback": function (settings) {
        var api = this.api();
        var rows = api.rows({page: 'current'}).nodes();
        var last = null;
        api.column(4, {page: 'current'}).data().each(function (group, i) {
            if (last !== group) {
                $(rows).eq(i).before(
                        '<tr class="group"><td colspan="8">' + group + '</td></tr>'
                        );
                last = group;
            }
        });
    },
});
//Customer Credit add event
jQuery(document).on('click', "#credit_add", function (e) {
    $(".manage_credits").load(base_url + 'customers/load_manage_credit', {credit_for: pay_customer}, function () {
        jQuery('#modal_manage_credits').modal('show');
        select2_dropdown();
    });
    e.preventDefault();
});
jQuery(document).on('submit', "#manage_credit_frm", function (event) {
    add_credit('manage_credit_frm');
    event.preventDefault();
});
function add_credit(formid)
{
    var data = jQuery("#" + formid).serialize();
    $("#" + formid + " .btn_notes .spinner").removeClass('hide');
    $(document).find("#" + formid + " .form-actions button").attr('disabled', 'disabled');
    jQuery.ajax({
        type: "POST",
        url: base_url + "customers/save_credit",
        data: data,
        success: function (data) {
            var result = data.split("^");
            if (result[0] == "0") {
                $(document).find("#" + formid + " .form-actions button").removeAttr('disabled');
                $("#" + formid + " .btn_notes .spinner").addClass('hide');
                $("#" + formid + " .userMessageDiv").html("");
                $("#" + formid + " .userMessageDiv").html(result[1]);
                $("#" + formid + " .userMessageDiv").show();
            } else {
                $("#" + formid + " .btn_notes .spinner").addClass('hide');
                var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button class="close" data-dismiss="alert"></button>Credit saved successfully. please wait while loading data in table.</div>';
                $("#" + formid + " .userMessageDiv").html(msg);
                $("#" + formid + " .userMessageDiv").show();
                setTimeout(function () {
                    get_customer_tab_detail('Credit', 'credit_tab');
//                        $('#notes_dttable').DataTable().ajax.reload(null, false);
                    $("#" + formid + " .userMessageDiv").hide();
                    jQuery('#modal_manage_credits').modal('hide');
                    remove_modal_backdrop();
                    remove_modal('modal_manage_credits');
                    $(document).find("#" + formid + " .form-actions button").removeAttr('disabled');
                    jQuery("#" + formid)[0].reset();
                }, 2000);
            }
        }
    });
}

function get_total_customer_credit(customer_id)
{
    jQuery.ajax({
        type: "POST",
        url: base_url + "customers/customer_credit",
        data: {'customer_id': btoa(pay_customer)},
        success: function (data) {
            $(document).find('.customer_total_credit').text(data);
        }
    });
}
function get_customer_lastmonth_credit(customer_id) {
    jQuery.ajax({
        type: "POST",
        url: base_url + "customers/get_customer_last_month_credit/" + pay_customer,
        data: {'get_data': true},
        success: function (data) {
            var result = data.split("^");
            if (result[0] == "0") {
                $(document).find('.customer_last_month_credit .icon-info22').remove();
            } else {
                if ($(document).find('.customer_last_month_credit .icon-info22').length <= 0) {
                    $(document).find('.customer_last_month_credit').prepend('<i class="text-danger icon-info22"></i> ');
                }
            }
        }
    });
}

jQuery(document).on('click', ".credit_delete", function (event) {
    var credit_id = $(this).attr('data-credit');
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
                    delete_credit(credit_id);
                    return true;
                } else {
                    return false;
                }
            });
    return false;
});
function delete_credit(note_id) {
    $.ajax({
        url: base_url + 'customers/delete_credit',
        type: "post",
        data: {'id': note_id},
        success: function (response) {
            get_customer_tab_detail('Credit', 'credit_tab');
            swal("Deleted!", "Note deleted successfully.", "success");
            setTimeout(function () {
                $('.sa-button-container .confirm').trigger('click');
            }, 4000);
        }
    });
}

//End of customer credi tab

//Eviction tab

$('#customer_evictions_dttable').dataTable({
    autoWidth: false,
    processing: true,
    serverSide: true,
    language: {
        search: '<span>Search:</span> _INPUT_',
        lengthMenu: '<span>Show:</span> _MENU_',
        paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
        emptyTable: 'No notifications available.'
    },
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    order: [[0, "asc"]],
    ajax: base_url + 'customers/get_notification/' + pay_customer,
    columns: [
        {
            "data": "id",
            width: "5%",
            "visible": true
        },
        {
            "data": "customer_name",
            width: "20%",
            "visible": true
        },
        {
            "data": "c_address",
            width: "40%",
            "visible": true,
            "render": function (data, type, full, meta) {
                var str = "";
                if (full.property_id) {
                    if (full.p_address != "") {
                        str += full.p_address + ',&nbsp;&nbsp;';
                    }
                    if (full.p_city != "") {
                        str += full.p_city + ',&nbsp;&nbsp;';
                    }
                    if (full.p_state != "") {
                        str += full.p_state + ',&nbsp;&nbsp;';
                    }
                    if (full.p_zip_code != "") {
                        str += full.p_zip_code + ',&nbsp;&nbsp;';
                    }
                    if (full.p_country != "") {
                        str += full.p_country;
                    }
                } else {
                    if (full.c_address != "") {
                        str += full.c_address + ',&nbsp;&nbsp;';
                    }
                    if (full.c_city != "") {
                        str += full.c_city + ',&nbsp;&nbsp;';
                    }
                    if (full.c_state != "") {
                        str += full.c_state + ',&nbsp;&nbsp;';
                    }
                    if (full.c_zip_code != "") {
                        str += full.c_zip_code + ',&nbsp;&nbsp;';
                    }
                    if (full.c_country != "") {
                        str += full.c_country;
                    }
                }
                return str;
            }
        },
        {
            "data": "type",
            width: "8%",
            "visible": true,
            render: function (data, type, full, meta) {
                var str = data;
                if (data.toLowerCase() == 'notification') {
                    str = '<span class="label bg-pink-300">' + data + '</span>';
                } else if (data.toLowerCase() == 'eviction') {
                    str = '<span class="label bg-teal-300">' + data + '</span>';
                }
                return str;
            },
        },
        {
            "data": "updated_time",
            width: "14%",
            "visible": true
        },
        {
            "visible": true,
            width: "8%",
            "sortable": false,
            render: function (data, type, full, meta) {
                var n_type = full.type;
                var str = '<ul class="icons-list"><li class="dropdown">';
                str += '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a>';
                str += '<ul class="dropdown-menu dropdown-menu-right">';
                if (n_type.toLowerCase() == 'notification') {
                    str += '<li><a href="javascript:void(0);" data-notice=' + btoa(full.id) + ' class="notification_resend"><i class="icon-mail5"></i> Resend</a></li>';
                }
                str += '<li><a href="javascript:void(0);" data-notice=' + btoa(full.id) + ' class="notification_delete"><i class="icon-trash"></i> Delete</a></li>';
                str += '</ul></li></ul>';
                str += '</ul>';
                return str;
            },
        }
    ],
});
jQuery(document).on('click', '#send_notice', function (e) {
    $(".eviction_modal_div").load(base_url + 'customers/eviction_email_view', {id: btoa(pay_customer)}, function () {
        CKEDITOR.replace('message', {
            height: 'auto',
            extraPlugins: 'forms'
        });
        select2_dropdown();
        $('#eviction_modal').modal('show');
    });
    e.preventDefault();
});
jQuery(document).on('change', "#notify_type", function () {
    var notify_type = $(this).val();
    $(".notice_div").load(base_url + 'customers/eviction_email_view', {id: btoa(pay_customer), notify_type: notify_type}, function () {
        if (notify_type == 'notification') {
            $('.btn_send_notice').find('.btn_lbl').html('Send to customer');
            select2_dropdown();
            CKEDITOR.replace('message', {
                height: 'auto',
                extraPlugins: 'forms'
            });
        } else if (notify_type == 'eviction') {
            $('.btn_send_notice').find('.btn_lbl').html('Save');
            styled_checkbox();
            $('.daterange-single').daterangepicker({
                singleDatePicker: true,
                parentEl: '#eviction_modal',
                minDate: new Date()
            });
            $('.s_date').daterangepicker({
                singleDatePicker: true,
                drops: 'up',
                parentEl: '#eviction_modal',
                minDate: new Date()
            });
            $('.rent_date').daterangepicker({
                singleDatePicker: true,
                parentEl: '#eviction_modal',
                allowInputToggle: true
//                maxDate: new Date()
            });
            $('.curreny_format').formatter({
                pattern: '{{99999}}.{{99}}'
            });
            $('.phone_format').formatter({
                pattern: '({{999}}) {{999}} - {{9999}}'
            });
        }
    });
});
jQuery(document).on('click', ".btn_send_notice", function (event) {
    var formid = 'customer_send_notice_frm';
    for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }
    var data = $(document).find("#" + formid).serialize();
//    var data = new FormData($(document).find("#" + formid)[0]);
    $("#" + formid + " .userMessageDiv").hide();
    jQuery('#' + formid + ' .spinner').removeClass('hide');
    $(document).find('.btn_send_email .frm-action button').attr('disabled', 'disabled');
    $.post(base_url + "customers/send_notice_to_customer", data,
            function (data) {
                $(document).find('.btn_send_notice .frm-action button').removeAttr('disabled');
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
                        $('#eviction_modal').modal('hide');
                        $('#eviction_modal').on('hidden.bs.modal', function () {
                            $('#eviction_modal').parent('div').html('');
                        });
                        $("#" + formid + " .userMessageDiv").hide();
                        get_customer_tab_detail('Eviction', 'eviction_tab');
                    }, 2000);
                }

            });
    event.preventDefault();
});
jQuery(document).on('click', '.notification_resend', function (e) {
    var notice_id = $(this).data('notice');
    $(".eviction_modal_div").load(base_url + 'customers/eviction_email_view', {id: btoa(pay_customer), notice_id: notice_id}, function () {
        CKEDITOR.replace('message', {
            height: 'auto',
            extraPlugins: 'forms'
        });
        select2_dropdown();
        $('#eviction_modal').modal('show');
    });
    e.preventDefault();
});
jQuery(document).on('click', ".notification_delete", function (event) {
    var notice_id = $(this).data('notice');
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
                    delete_notice(notice_id);
                    return true;
                } else {
                    return false;
                }
            });
    return false;
});
function delete_notice(notice_id) {
    $.ajax({
        url: base_url + 'customers/delete_notification',
        type: "post",
        data: {'id': notice_id},
        success: function (response) {
            var result = response.split("^");
            if (result[0] == "0") {
                swal("Not Deleted!", "Something Went wrong, Please try again.", "warning");
                setTimeout(function () {
                    $('.sa-button-container .confirm').trigger('click');
                }, 2000);
            } else {
                swal("Deleted!", "Notification deleted successfully.", "success");
                setTimeout(function () {
                    $('.sa-button-container .confirm').trigger('click');
                    $('#customer_evictions_dttable').DataTable().ajax.reload(null, false);
                }, 1000);
            }
        }
    });
}
jQuery(document).on('keypress keyup blur cut paste', ".rent_charged", function (event) {
    var price = ($(this).val() > 0) ? parseFloat($(this).val()) : 0;
    $(this).parent().siblings('td').find(".rent_owing").val(price.toFixed(2));
    caluclate_total();
});
jQuery(document).on('keypress keyup blur cut paste', ".rent_owing", function (event) {
    caluclate_total();
});
function caluclate_total() {
    var total_price = 0;
    $('.rent_owing').each(function (i) {
        total_price += ($(this).val() != '') ? parseFloat($(this).val()) : 0;
    });
    $(document).find('.total_rent_owing').val(total_price.toFixed(2));
}

//Contracts tab
$('#customer_contracts_dttable').dataTable({
    autoWidth: false,
    processing: true,
    serverSide: true,
    language: {
        search: '<span>Search:</span> _INPUT_',
        lengthMenu: '<span>Show:</span> _MENU_',
        paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
        emptyTable: 'No contracts available.'
    },
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    order: [[0, "asc"]],
    ajax: base_url + 'customers/get_contracts/' + pay_customer,
    columns: [
        {
            "data": "id",
            width: "5%",
            "visible": true
        },
        {
            "data": "name",
            width: "20%",
            "visible": true
        },
        {
            "data": "start_date",
            width: "10%",
            "visible": true,
        },
        {
            "data": "end_date",
            width: "10%",
            "visible": true,
        },
        {
            "data": "notification_email",
            width: "12%",
            "visible": true
        },
        {
            "data": "reminder_days",
            width: "14%",
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
                str += '<li><a href="javascript:void(0);" data-contract=' + btoa(full.id) + ' class="contract_edit"><i class="icon-pencil5"></i> Edit</a></li>';
                str += '<li><a href="javascript:void(0);" data-contract=' + btoa(full.id) + ' class="contract_delete"><i class="icon-trash"></i> Delete</a></li>';
                str += '</ul></li></ul>';
                str += '</ul>';
                return str;
            },
        }
    ],
});
jQuery(document).on('click', "#add_contract", function (e) {
    $(".manage_contract").load(base_url + 'customers/load_manage_contract', {contract_for: pay_customer}, function () {
        jQuery('#modal_manage_contract').modal('show');
        $('.daterange-single').daterangepicker({
            singleDatePicker: true,
            parentEl: '#modal_manage_contract',
        });
        $(".file_input").uniform({
            fileButtonClass: 'action btn bg-primary'
        });
    });
    e.preventDefault();
});
jQuery(document).on('click', ".contract_edit", function (event) {
    var contract_id = $(this).attr('data-contract');
    $(".manage_contract").load(base_url + 'customers/load_manage_contract', {contract_for: pay_customer, contract: contract_id}, function () {
        jQuery('#modal_manage_contract').modal('show');
        $('.daterange-single').daterangepicker({
            singleDatePicker: true,
            parentEl: '#modal_manage_contract',
        });
        $(".file_input").uniform({
            fileButtonClass: 'action btn bg-primary'
        });
        load_image();
    });
});
jQuery(document).on('click', ".delete_ci", function (event) {
    var id = this.id;
    $.ajax({
        url: base_url + 'customers/delete_contract_img',
        type: "post",
        data: {'id': btoa(id)},
        success: function (response)
        {
            if (response) {
                $('.ci_' + id).fadeOut(300, function () {
                    $(this).remove();
                });
            }
        }
    });
    event.preventDefault();
});
jQuery(document).on('click', ".contract_delete", function (event) {
    var contract_id = $(this).attr('data-contract');
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
                    delete_contract(contract_id);
                    return true;
                } else {
                    return false;
                }
            });
    return false;
});
function delete_contract(contract_id) {
    $.ajax({
        url: base_url + 'customers/delete_contract',
        type: "post",
        data: {'id': contract_id},
        success: function (response) {
            var result = response.split("^");
            if (result[0] == "0") {
                swal("Not Deleted!", "Something Went wrong, Please try again.", "warning");
                setTimeout(function () {
                    $('.sa-button-container .confirm').trigger('click');
                }, 4000);
            } else {
                $('#customer_contracts_dttable').DataTable().ajax.reload(null, false);
                swal("Deleted!", "Contract deleted successfully.", "success");
                setTimeout(function () {
                    $('.sa-button-container .confirm').trigger('click');
                }, 4000);
            }
        }
    });
}

jQuery(document).on('submit', "#manage_contract_frm", function (event) {
    add_contract('manage_contract_frm');
    event.preventDefault();
});
function add_contract(formid) {
    $(document).find("#" + formid + ' .btn_contract .spinner').removeClass('hide');
    $(document).find("#" + formid + ' .form-actions button').attr('disabled', 'disabled');
    var data = new FormData($(document).find("#" + formid)[0]);
    jQuery.ajax({
        type: "POST",
        url: base_url + "customers/save_contract",
        data: data,
        async: true,
        contentType: false,
        processData: false,
        success: function (data) {
            var result = data.split("^");
            if (result[0] == "0") {
                $(document).find("#" + formid + " .form-actions button").removeAttr('disabled');
                $(document).find("#" + formid + " .btn_contract .spinner").addClass('hide');
                $("#" + formid + " .userMessageDiv").html("");
                $("#" + formid + " .userMessageDiv").html(result[1]);
                $("#" + formid + " .userMessageDiv").show();
            } else if (result[0] == "2") {
                $(document).find("#" + formid + " #contract").val(result[1]);
                $(document).find("#" + formid + " .form-actions button").removeAttr('disabled');
                $("#" + formid + " .btn_contract .spinner").addClass('hide');
                $("#" + formid + " .userMessageDiv").html("");
                $("#" + formid + " .userMessageDiv").html(result[2]);
                $("#" + formid + " .userMessageDiv").show();
            } else {
                $(document).find("#" + formid + " .btn_contract .spinner").addClass('hide');
                var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button class="close" data-dismiss="alert"></button>Contract saved successfully. please wait while loading data in table.</div>';
                $("#" + formid + " .userMessageDiv").html(msg);
                $("#" + formid + " .userMessageDiv").show();
                setTimeout(function () {
                    $('#customer_contracts_dttable').DataTable().ajax.reload(null, false);
                    $("#" + formid + " .userMessageDiv").hide();
                    jQuery('#modal_manage_contract').modal('hide');
                    remove_modal_backdrop();
                    $(document).find("#" + formid + " .form-actions button").removeAttr('disabled');
                    jQuery("#" + formid)[0].reset();
                }, 2000);
            }
        }
    });
}
//Contracts tab


// Strat Invoice Tab
jQuery(document).on('click', ".invoice_view", function (event) {
    var invoice_id = $(this).data('invoice');
    var type = $(this).data('type');
    $('#pdf_iframe').attr('src', '');
    $(".invoice_preview").modal('show');
    $(".invoice_preview .modal-title").html('Invoice PDF');
    if (type == 'pending' || type == 'fail' || type == 'paid') {
        $(".invoice_preview .send_pdf_to_customer").show();
    } else {
        $(".invoice_preview .send_pdf_to_customer").hide();
    }
    $(".pdf_message_div").html('');
    $(".view_pdf_section").hide();
    $(".pdf_actions").hide();
    $('#pdf_iframe').attr('src', '');
    $(".pdf_loading_timer").show();
    $("#pdf_name").val('');
    $("#invoice_id").val('');
    $.ajax({
        url: base_url + 'customers/check_invoice_exist/' + invoice_id,
        type: "post",
        success: function (response)
        {
            var result = response.split("^");
            if (result[0] == "1") {
                $("#invoice_id").val(invoice_id);
                $(".pdf_actions").fadeIn();
                $('#pdf_iframe').attr('src', base_url + "customers/generate_pdf_invoice/" + invoice_id + '#zoom=100');
                $('.print_pdf').attr('href', base_url + "customers/generate_pdf_invoice/" + invoice_id + '/print');
                $('.download_pdf').attr('href', base_url + "customers/generate_pdf_invoice/" + invoice_id + '/download');
                $(".pdf_loading_timer").hide();
                $(".view_pdf_section").show();
            } else {
                $(".pdf_actions").fadeOut();
                $(".generate_pdf").modal('hide');
                var msg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"></button>Some thing went wrong.PDF could not be generated.</div>';
                $(".userMessageDiv").html(msg);
                $(".userMessageDiv").show();
                jQuery('#loading').hide();
                setTimeout(function () {
                    $(".userMessageDiv").hide();
                }, 2000);
            }
        }
    });
});
jQuery(document).on('click', ".send_pdf_to_customer", function (event) {
    var invoice_id = $("#invoice_id").val();
    send_pdf_to_customer(invoice_id);
    event.preventDefault();
});
function send_pdf_to_customer(invoice_id) {
    $(document).find('.send_pdf_to_customer').attr('disabled', 'disabled');
    $(document).find('.send_pdf_to_customer .spinner').removeClass('hide');
    $.ajax({
        url: base_url + "customers/send_pdf_to_customer/" + invoice_id,
        type: "post",
        success: function (result)
        {
            $(document).find('.send_pdf_to_customer').removeAttr('disabled');
            $(document).find('.send_pdf_to_customer .spinner').addClass('hide');
            var msg = "";
            if (result == "1") {
                msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button class="close" data-dismiss="alert"></button>Email send successfully.</div>';
                get_log_data('invoice');
            } else if (result == "2") {
                msg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"></button>Please check with send email configuration settings.</div>';
            } else if (result == "3") {
                msg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"></button>Please check email template. Invoice template is disabled.</div>';
            } else {
                msg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"></button>Email could not be sent. Please contact to your service provider.</div>';
            }
            jQuery(".pdf_message_div").html(msg);
            jQuery(".pdf_message_div").show();
            setTimeout(function () {
                $(".pdf_message_div").hide();
            }, 4000);
        }
    });
}

jQuery("#customer_invoice_dttable").on('click', '.paid_payment', function () {
    var invoice_id = $(this).attr('data-invoice');
    swal({
        title: "Are you sure?",
        text: "This will mark invoice as status PAID !!",
        type: "warning",
        confirmButtonText: "Yes",
        showCancelButton: true,
        closeOnConfirm: false,
        showLoaderOnConfirm: true
    },
            function (isConfirm) {
                if (isConfirm) {
                    payment_paid(invoice_id);
                    return true;
                } else {
                    return false;
                }
            });
    return false;
});

function payment_paid(invoice_id) {
    $.ajax({
        //url: base_url + 'customers/payment_paid',
        url: base_url + 'home/payment_paid',
        type: "post",
        data: {'id': invoice_id},
        success: function (response)
        {
            if (response == '1')
            {
                swal("Success !", "Invoice mark as PAID successfully.", "success");
                setTimeout(function () {
                    $('.sa-button-container .confirm').trigger('click');
                    $('#customer_invoice_dttable').DataTable().ajax.reload(null, false);
                }, 2000);
            }
        }
    });
}
$('#customer_invoice_dttable').dataTable({
    autoWidth: false,
    processing: true,
    serverSide: true,
    language: {
        search: '<span>Search:</span> _INPUT_',
        lengthMenu: '<span>Show:</span> _MENU_',
        paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
        emptyTable: 'No invoices available.'
    },
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    order: [[0, "desc"]],
    ajax: base_url + 'customers/get_invoices/' + pay_customer,
    columns: [
        {
            "data": "id",
            width: "5%",
            "visible": true
        },
        {
            "data": "created",
            width: "18%",
            "visible": true
        },
        {
            "data": "detail",
            width: "20%",
            "visible": true
        },
        {
            "data": "price",
            width: "8%",
            "visible": true,
        },
        {
            "data": "month",
            "bVisible": false
        },
        {
            "data": "payment_type",
            width: "12%",
            "visible": true,
        },
        {
            "data": "ccnumber",
            width: "10%",
            "visible": true,
            render: function (data, type, full, meta) {
                var str = '-';
                if (full.payment_type == 'Credit') {
                    str = data;
                }
                return str;
            }
        },
        {
            "data": "card_type",
            width: "10%",
            "visible": true,
            render: function (data, type, full, meta) {
                var str = '-';
                if (full.payment_type == 'Credit') {
                    str = data;
                }
                return str;
            }
        },
        {
            "data": "payment_status",
            width: "12%",
            "visible": true,
            render: function (data, type, full, meta) {
                var str = ''
                if (data == "fail") {
                    var failture_message = "-";
                    var failture_reason = "-";
                    if (typeof (full.failture_reason) != "undefined" && full.failture_reason != null) {
                        failture_reason = full.failture_reason;
                    }
                    if (typeof (full.failture_message) != "undefined" && full.failture_message != null) {
                        failture_message = full.failture_message;
                    }
                    var popup_content = '<div><strong>Failure reason : </strong>' + failture_reason + '</div>'
                            + '<div><strong>Failure message : </strong>' + failture_message + '</div>';
                }

                if (data == "paid") {
                    str = '<label class="label bg-success">' + data + '</label>';
                } else if (data == "pending") {
                    str = '<a class="icon huge paid_payment label bg-warning"  data-invoice=' + btoa(full.id) + ' id="' + full.id + '" href="javascript:;" >' + data + '</a>';
                } else {
                    if (data == 'fail') {
                        var invoice_id = "-";
                        if (typeof (full.invoice_id) != "undefined" && full.invoice_id != null) {
                            invoice_id = full.invoice_id;
                        }
                        str = '<label class="label bg-danger popovercls"  data-popup="popover-custom" title="Invoice ID : ' + invoice_id + '" data-trigger="hover" data-content="' + popup_content + '">' + data + '</label>';
                    } else {
                        str = '<label class="label bg-danger">' + data + '</label>';
                    }
                }
                return str;
            }
        },
        {
            "visible": true,
            width: "5%",
            "sortable": false,
            render: function (data, type, full, meta) {
                var str = '<ul class="icons-list"><li class="dropdown">';
                str += '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a>';
                str += '<ul class="dropdown-menu dropdown-menu-right">';
                if (full.payment_status != 'paid') {
                    str += '<li><a href="javascript:void(0);" data-invoice=' + btoa(full.id) + ' class="invoice_edit"><i class="icon-pencil5"></i> Edit</a></li>';
                }
                str += '<li><a href="javascript:void(0);" data-invoices=' + full.id + ' data-invoice=' + btoa(full.id) + ' class="invoice_delete"><i class="icon-trash"></i> Delete</a></li>';
                str += '<li><a href="javascript:void(0);" data-invoice=' + btoa(full.id) + ' data-type=' + full.payment_status + ' class="invoice_view"><i class="icon-zoomin3"></i> View</a></li>';
                str += '</ul></li></ul>';
                str += '</ul>';
                return str;
            },
        }
    ],
    "drawCallback": function (settings) {
        $('[data-popup=popover-custom]').popover({
            html: true,
            template: '<div class="popover border-primary-600"><div class="arrow"></div><h3 class="popover-title bg-primary-600"></h3><div class="popover-content"></div></div>'
        });
        var api = this.api();
        var rows = api.rows({page: 'current'}).nodes();
        var last = null;
        api.column(4, {page: 'current'}).data().each(function (group, i) {
            if (last !== group) {
                $(rows).eq(i).before(
                        '<tr class="group"><td colspan="9">' + group + '</td></tr>'
                        );
                last = group;
            }
        });
    },
});
jQuery(document).on('click', "#add_invoice", function (event) {
    $(".manage_invoice").load(base_url + 'customers/load_manage_invoice', {invoice_for: pay_customer}, function () {
        jQuery('#modal_manage_invoice').modal('show');
        styled_checkbox();
        select2_dropdown();
        $(document).find('.daterange-single').daterangepicker({
            singleDatePicker: true,
            parentEl: '#modal_manage_invoice',
            timePicker: true,
            applyClass: 'bg-slate-600',
            cancelClass: 'btn-default',
            locale: {
                format: 'MM/DD/YYYY h:mm a'
            }
        });
    });
});
jQuery(document).on('click', "#c_include_tax", function (event) {
    calculate_manually_invoice_price_tax();
});
jQuery(document).on('keypress keyup focusin blur cut paste', "#c_price", function (event) {
    calculate_manually_invoice_price_tax();
});
function calculate_manually_invoice_price_tax()
{
    var tax_per = 0;
    var price = $.trim($("#c_price").val());
    price = parseFloat(price.replace(',', ''));
    var m_country = $("#c_country").val();
    var m_state = $("#c_state").val();
    var include_tax = 0;
    var tax_amount = gross_price = grand_total = 0;
    $("#c_invoice_total_price").text('0');
    $("#c_invoice_tax").text('0');
    $("#c_invoice_fprice").text('0');
    if (price > 0) {
        if (m_country == "Canada") {
            tax_per = 13;
            if (m_state == "QC" || m_state == "Quebec") {
                tax_per = 5;
            }
            include_tax = $('#c_include_tax').is(":checked")
        } else {
            tax_per = 0;
            include_tax = 1;
        }

        if (include_tax) {
            gross_price = price * 100 / (100 + parseFloat(tax_per));
            tax_amount = parseFloat(price) - parseFloat(gross_price);
            grand_total = parseFloat(price);
        } else {
            gross_price = price;
            tax_amount = (price * tax_per / 100);
            grand_total = parseFloat(price) + parseFloat(tax_amount);
        }

        $("#m_tax_per").val(tax_per);
        $("#c_invoice_total_price").text(grand_total.toFixed(2));
        $("#c_invoice_tax").text(tax_amount.toFixed(2));
        $("#c_invoice_fprice").text(grand_total.toFixed(2));
        $("#calculated_final_price").val(grand_total.toFixed(2));
        $("#calculated_tax").val(tax_amount.toFixed(2));
    }
}
// manually invoice gentrate End

jQuery(document).on('keypress keyup blur cut paste', "#package_price", function (event) {
    var term = $(this).data('term');
    var price = ($(this).val() > 0) ? parseFloat($(this).val()) : 0;
    var tp = price * term;
    $(document).find("#package_total_price").val(tp.toFixed(2));
    caluclate_price();
});
jQuery(document).on('keypress keyup blur cut paste', ".addon_ch", function (event) {
    var term = $(this).data('term');
    var id = this.id;
    var price = ($(this).val() > 0) ? parseFloat($(this).val()) : 0;
    var tp = price * term;
    $(document).find("#addon_total_" + id).val(tp.toFixed(2));
    caluclate_price();
});
jQuery(document).on('keypress keyup blur cut paste', ".cal_price", function (event) {
    caluclate_price();
});
function caluclate_price() {
    var total_price = 0;
    $('.cal_price').each(function (i) {
        total_price += ($(this).val() != '') ? parseFloat($(this).val()) : 0;
    });
    $(document).find('#total_price').val(total_price.toFixed(2));
    var tax_per = $(document).find('#tax_val').val();
    var tax_amt = (total_price * tax_per) / 100;
    $(document).find('#tax_amt').val(tax_amt.toFixed(2));
    var final_total = total_price += tax_amt;
    $(document).find('#grand_total').val(final_total.toFixed(2));
}


//edit invoice calculation



//invoice delete
jQuery(document).on('click', ".invoice_delete", function (event) {
    var invoice_id = $(this).attr('data-invoice');
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
                    delete_invoice(invoice_id);
                    return true;
                } else {
                    return false;
                }
            });
    return false;
});
function delete_invoice(invoice_id) {
    $.ajax({
        url: base_url + 'customers/delete_invoice',
        type: "post",
        data: {'id': invoice_id},
        success: function (response)
        {
            if (response == '1')
            {
                $('#customer_invoice_dttable').DataTable().ajax.reload(null, false);
                swal("Deleted!", "Invoice deleted successfully.", "success");
                setTimeout(function () {
                    $('.sa-button-container .confirm').trigger('click');
                }, 4000);
            } else {
                swal("Deleted!", "Invoice could not be deleted.", "warning");
            }
        }
    });
}

jQuery(document).on('click', ".invoice_edit", function (event) {
    var invoice_id = $(this).attr('data-invoice');
    $(".manage_invoice").load(base_url + 'customers/load_manage_invoice', {invoice_for: pay_customer, invoice: invoice_id}, function () {
        jQuery('#modal_manage_invoice').modal('show');
        select2_dropdown();
        styled_checkbox();
        $(document).find('.daterange-single').daterangepicker({
            singleDatePicker: true,
            parentEl: '#modal_manage_invoice',
            timePicker: true,
            applyClass: 'bg-slate-600',
            cancelClass: 'btn-default',
            locale: {
                format: 'MM/DD/YYYY h:mm a'
            }
        });
    });
});
jQuery(document).on('submit', "#manage_invoice_frm", function (event) {
    add_manual_invoice('manage_invoice_frm');
    event.preventDefault();
});
function add_manual_invoice(formid) {
    $(document).find("#" + formid + ' .btn_invoice .spinner').removeClass('hide');
    $(document).find("#" + formid + ' .form-actions button').attr('disabled', 'disabled');
    var data = new FormData($(document).find("#" + formid)[0]);
    jQuery.ajax({
        type: "POST",
        url: base_url + "customers/add_manually_invoice",
        data: data,
        async: true,
        contentType: false,
        processData: false,
        success: function (data) {
            var result = data.split("^");
            if (result[0] == "0") {
                $(document).find("#" + formid + " .form-actions button").removeAttr('disabled');
                $(document).find("#" + formid + " .btn_invoice .spinner").addClass('hide');
                $("#" + formid + " .userMessageDiv").html("");
                $("#" + formid + " .userMessageDiv").html(result[1]);
                $("#" + formid + " .userMessageDiv").show();
            } else if (result[0] == "2") {
                $(document).find("#" + formid + " #contract").val(result[1]);
                $(document).find("#" + formid + " .form-actions button").removeAttr('disabled');
                $("#" + formid + " .btn_contract .spinner").addClass('hide');
                $("#" + formid + " .userMessageDiv").html("");
                $("#" + formid + " .userMessageDiv").html(result[2]);
                $("#" + formid + " .userMessageDiv").show();
            } else {
                $(document).find("#" + formid + " .btn_contract .spinner").addClass('hide');
                var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button class="close" data-dismiss="alert"></button>Invoice saved successfully. please wait while loading data in table.</div>';
                $("#" + formid + " .userMessageDiv").html(msg);
                $("#" + formid + " .userMessageDiv").show();
                setTimeout(function () {
                    $('#customer_invoice_dttable').DataTable().ajax.reload(null, false);
                    $("#" + formid + " .userMessageDiv").hide();
                    jQuery('#modal_manage_invoice').modal('hide');
//                    remove_modal_backdrop();
                    $(document).find("#" + formid + " .form-actions button").removeAttr('disabled');
                    jQuery("#" + formid)[0].reset();
                }, 2000);
            }
        }
    });
}
// End Invoice Tab