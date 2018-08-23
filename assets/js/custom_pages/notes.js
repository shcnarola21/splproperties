$('#notes_dttable').dataTable({
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
    ajax: base_url + 'notes/get_notes',
    columns: [
        {
            "data": "id",
            width: "5%",
            "visible": true
        },
        {
            "data": "customer_name",
            width: "10%",
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
                str += '<li><a href="javascript:void(0);" data-note=' + btoa(full.id) + ' class="p_note_edit"><i class="icon-pencil5"></i> Edit</a></li>';
                str += '<li><a href="javascript:void(0);" data-note=' + btoa(full.id) + ' class="note_delete"><i class="icon-trash"></i> Delete</a></li>';
                str += '</ul></li></ul>';
                str += '</ul>';
                return str;
            },
        }
    ],
});
jQuery(document).on('click', "#p_note_add", function (e) {
    $(".manage_notes").load(base_url + 'notes/load_manage_notes', function () {
        jQuery('#modal_manage_notes').modal('show');
        select2_dropdown_with_search();
    });
    e.preventDefault();
});
jQuery(document).on('click', ".p_note_edit", function (event) {
    var note_id = $(this).attr('data-note');
    $(".manage_notes").load(base_url + 'notes/load_manage_notes', {note: note_id}, function () {
        jQuery('#modal_manage_notes').modal('show');
        select2_dropdown_with_search();
    });
});