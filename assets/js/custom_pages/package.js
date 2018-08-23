package_services();
function package_services() {
    $('.package_services').each(function (value, key) {
        var service = $(this).find('a').attr('data-service');
        if (service != 'bundle') {
            get_package_services(service);
            get_package_services(service, 'Addon');
            get_package_services(service, 'setupfee');
        } else {
            get_package_services(service);
        }
    });
}

function get_package_services(type, package_type) {
    if (typeof package_type == 'undefined') {
        package_type = 'basic';
    }
    var url = base_url + 'packages/get/' + type;
    if (package_type == 'Addon') {
        url = base_url + 'packages/get/' + type + '/Addon';
    } else if (package_type == 'setupfee') {
        url = base_url + 'packages/get/' + type + '/setupfee';
    }
    if (package_type != 'setupfee') {
        $(document).find('#' + package_type.toLowerCase() + '_dttable_' + type).dataTable({
            autoWidth: false,
            processing: true,
            serverSide: true,
            destroy: true,
            language: {
                search: '<span>Search:</span> _INPUT_',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
                emptyTable: 'No packages available.'
            },
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            order: [[0, "asc"]],
            ajax: url,
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
                    data: "price",
                    visible: true,
                },
                {
                    data: "type",
                    visible: true,
                },
                {
                    data: "action",
                    render: function (data, type, full, meta) {
                        var str = '<ul class="icons-list"><li class="dropdown">';
                        str += '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a>';
                        str += '<ul class="dropdown-menu dropdown-menu-right">';
                        str += '<li><a href="javascript:void(0);" data-package=' + btoa(full.id) + ' class="change_package"><i class="icon-pencil5"></i> Edit</a></li>';
                        if (package_type == 'Addon') {
                            str += '<li><a href="javascript:void(0);" data-package=' + btoa(full.id) + ' data-type="Addon" class="delete_package"><i class="icon-trash"></i> Delete</a></li>';
                        } else {
                            str += '<li><a href="javascript:void(0);" data-package=' + btoa(full.id) + ' data-type="Package" class="delete_package"><i class="icon-trash"></i> Delete</a></li>';
                        }
                        str += '</ul></li></ul>';
                        str += '</ul>';
                        return str;
                    },
                    sortable: false,
                },
            ],
        });
    } else {
        $('#' + package_type.toLowerCase() + '_dttable_' + type).dataTable({
            autoWidth: false,
            processing: true,
            serverSide: true,
            destroy: true,
            language: {
                search: '<span>Search:</span> _INPUT_',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
                emptyTable: 'No setupfee available.'
            },
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            order: [[0, "asc"]],
            ajax: url,
            columns: [
                {
                    data: "id",
                    visible: true,
                    sortable: false
                },
                {
                    data: "provience",
                    visible: true,
                },
                {
                    data: "fees",
                    visible: true,
                },
                {
                    data: "action",
                    render: function (data, type, full, meta) {
                        var str = '<ul class="icons-list"><li class="dropdown">';
                        str += '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a>';
                        str += '<ul class="dropdown-menu dropdown-menu-right">';
                        str += '<li><a href="javascript:void(0);" data-fee=' + btoa(full.id) + ' data-service=' + btoa(full.service_id) + ' class="change_setupfee"><i class="icon-pencil5"></i> Edit</a></li>';
                        str += '<li><a href="javascript:void(0);" data-fee=' + btoa(full.id) + ' data-service=' + btoa(full.service_id) + ' class="delete_setupfee"><i class="icon-trash"></i> Delete</a></li>';
                        str += '</ul></li></ul>';
                        str += '</ul>';
                        return str;
                    },
                    sortable: false,
                },
            ],
        });
//        var add_button = '<div class="text-right"><button data-sid="' + type + '" type="button" class="btn btn-primary btn-labeled text-right btn-sm add_setup_fee_btn" data-toggle="modal" id="add_setup_fee" style="margin-left: auto;"><b><i class="icon-plus-circle2"></i></b> Add Setup Fee</button></div>';
//        $('#' + package_type.toLowerCase() + '_dttable_' + type).parents('.setup_fee_div').find('.datatable-header').append(add_button);
    }
}