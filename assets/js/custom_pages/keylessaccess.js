property_keylessaccess();

// Format icon
function iconFormat(icon) {
    var originalOption = icon.element;
    if (!icon.id) {
        return icon.text;
    }
    var $icon = "<i class='icon-" + $(icon.element).data('icon') + "'></i>" + icon.text;

    return $icon;
}

function property_keylessaccess(property) {
    if (typeof property != 'undefined') {
        get_property_keylessaccess(property);
        $(document).find('.add_fob_btn').data('property', property);
    } else {
        $('.property_access').each(function (value, key) {
            var property = $(this).find('a').data('property');
            get_property_keylessaccess(property);

        });
    }
}
function get_property_keylessaccess(property) {
    if (property != '') {
        var url = base_url + 'keylessaccess/get/' + property;

        $(document).find('#keyless_access_dttable_' + property).dataTable({
            autoWidth: false,
            processing: true,
            serverSide: true,
            destroy: true,
            language: {
                search: '<span>Search:</span> _INPUT_',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
                emptyTable: 'No Keyless access available.'
            },
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            order: [[0, "asc"]],
            ajax: url,
            columns: [
                {
                    "data": "fob",
                    width: "8%",
                    "visible": true
                },
                {
                    "data": "password",
                    width: "10%",
                    "visible": true
                },
                {
                    "data": "address",
                    width: "25%",
                    "visible": true,
                    render: function (data, type, full, meta) {
                        var str = '';
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
                        return str;
                    }
                },
                {
                    "data": "unit_no",
                    width: "11%",
                    "visible": true,
                    "render": function (data, type, full, meta) {
                        if (full.customer_name) {
                            var popup_content = "<div><strong>Rating : </strong><input type='hidden'  class='rating' data-readonly value='" + full.rating + "' data-filled='icon-star-full2 symbol-filled' data-empty='icon-star-empty3 symbol-empty'></div>"
                                    + "<div><strong>Name : </strong>" + full.customer_name + "</div>"
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
                            }
                        } else {
                            var popup_content = "<div>Unit Vacant</div>";
                        }
                        return '<label class="popovercls" data-popup="popover-custom-unit" title="Customer Info" data-trigger="hover" data-content="' + popup_content + '">Unit: ' + data + '</label>';
                    }
                },
                {
                    "data": "zone_names",
                    width: "30%",
                    "visible": true,
                    render: function (data, type, full, meta) {
                        var str = '';
                        var zone_id = full.zone_id;
                        var zones = zone_id.split(',');
                        var zone_info = '';
                        if (full.zone_info) {
                            zone_info = full.zone_info;
                        }
                        if (zones.length > 0) {
                            for (var i = 0; i < zones.length; i++) {
                                if (zone_info[zones[i]]) {
                                    var current_zone_info = zone_info[zones[i]];
                                    var popup_content = '<div><strong>Zone : </strong><span>' + current_zone_info.name + '</span></div>'
                                            + '<div><strong>Password : </strong><span>' + current_zone_info.password + '</span></div>';
                                    str += '<label data-popup="popover-custom" title="Zone Info" data-trigger="hover" data-placement="top" data-content="' + popup_content + '">' + current_zone_info.name + '</label>,&nbsp;'
                                }
                            }
                        }
                        if (str != '') {
                            var lastChar = str.slice(-7);
                            if (lastChar == ',&nbsp;') {
                                str = str.slice(0, -7);
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
                        str += '<li><a href="javascript:void(0);" data-keyless=' + btoa(full.id) + ' data-property=' + full.property_id + ' class="edit_fob_btn"><i class="icon-pencil5"></i> Edit</a></li>';
                        str += '<li><a href="javascript:void(0);" data-keyless=' + btoa(full.id) + ' data-property=' + btoa(full.property_id) + ' class="delete_fob_btn"><i class="icon-trash"></i> Delete</a></li>';
                        str += '</ul></li></ul>';
                        str += '</ul>';
                        return str;
                    },
                }
            ],
            "fnDrawCallback": function (oSettings) {
                $('[data-popup=popover-custom-unit]').popover({
                    html: true,
                    template: '<div class="popover border-primary-600"><div class="arrow"></div><h3 class="popover-title bg-primary-600"></h3><div class="popover-content"></div></div>',
                    trigger: 'hover'

                }).on('shown.bs.popover', function () {
                    $('body .popover .rating').rating();
                });
                $('[data-popup=popover-custom]').popover({
                    html: true,
                    template: '<div class="popover border-primary-600"><div class="arrow"></div><h3 class="popover-title bg-primary-600"></h3><div class="popover-content"></div></div>'
                });
            },
        });
        datatable_select2();
    }
}
jQuery(document).on('click', ".add_fob_btn", function (e) {
    var property = $(this).data('property');
    $(".manage_keyless_access_div").load(base_url + 'keylessaccess/load_manage_keylessaccess', {property: property}, function () {
        $(document).find(".select-zones").select2({
            templateResult: iconFormat,
            minimumResultsForSearch: Infinity,
            templateSelection: iconFormat,
            escapeMarkup: function (m) {
                return m;
            }
        });
        select2_dropdown();
        jQuery('#modal_manage_keyless_access').modal('show');

    });
    e.preventDefault();
});
jQuery(document).on('click', ".edit_fob_btn", function (event) {
    var keyless_id = $(this).attr('data-keyless');
    var property = $(this).data('property');
    $(".manage_keyless_access_div").load(base_url + 'keylessaccess/load_manage_keylessaccess', {keyless_access: keyless_id, property: property}, function () {
        $(document).find(".select-zones").select2({
            templateResult: iconFormat,
            minimumResultsForSearch: Infinity,
            templateSelection: iconFormat,
            escapeMarkup: function (m) {
                return m;
            }
        });
        select2_dropdown();
        jQuery('#modal_manage_keyless_access').modal('show');
    });
});

jQuery(document).on('submit', "#manage_keyless_access_frm", function (event) {
    add_keyless_access('manage_keyless_access_frm');
    event.preventDefault();
});
function add_keyless_access(formid)
{
    var data = jQuery("#" + formid).serialize();
    var property = $('#property_id').val();
    property = atob(property);
    $("#" + formid + " .btn_keyless_access .spinner").removeClass('hide');
    $(document).find("#" + formid + " .form-actions button").attr('disabled', 'disabled');
    jQuery.ajax({
        type: "POST",
        url: base_url + "keylessaccess/save",
        data: data,
        success: function (data) {
            var result = data.split("^");
            if (result[0] == "0") {
                $(document).find("#" + formid + " .form-actions button").removeAttr('disabled');
                $("#" + formid + " .btn_keyless_access .spinner").addClass('hide');
                $("#" + formid + " .userMessageDiv").html("");
                $("#" + formid + " .userMessageDiv").html(result[1]);
                $("#" + formid + " .userMessageDiv").show();
            } else {
                $("#" + formid + " .btn_keyless_access .spinner").addClass('hide');
                var msg = '<div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered"><button class="close" data-dismiss="alert"></button>Keyless Access saved successfully. please wait while loading data in table.</div>';
                $("#" + formid + " .userMessageDiv").html(msg);
                $("#" + formid + " .userMessageDiv").show();
                setTimeout(function () {
                    $('#keyless_access_dttable_' + property).DataTable().ajax.reload(null, false);
                    $("#" + formid + " .userMessageDiv").hide();
                    jQuery('#modal_manage_keyless_access').modal('hide');
                    remove_modal_backdrop();
                    $(document).find("#" + formid + " .form-actions button").removeAttr('disabled');
                    jQuery("#" + formid)[0].reset();
                }, 2000);
            }
        }
    });
}



jQuery(document).on('click', ".delete_fob_btn", function (event) {
    var keyless_id = $(this).attr('data-keyless');
    var property = atob($(this).attr('data-property'));
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
                    delete_keylessaccess(keyless_id, property);
                    return true;
                } else {
                    return false;
                }
            });
    return false;
});
function delete_keylessaccess(keyless_id, property) {
    $.ajax({
        url: base_url + 'keylessaccess/delete_keylessaccess',
        type: "post",
        data: {'id': keyless_id},
        success: function (response) {
            var result = response.split("^");
            if (result[0] == "0") {
                swal("Not Deleted!", "Something Went wrong, Please try again.", "warning");
                setTimeout(function () {
                    $('.sa-button-container .confirm').trigger('click');
                }, 2000);
            } else {
                $('#keyless_access_dttable_' + property).DataTable().ajax.reload(null, false);
                swal("Deleted!", "Keyless access deleted successfully.", "success");
                setTimeout(function () {
                    $('.sa-button-container .confirm').trigger('click');
                }, 2000);
            }
        }
    });
}