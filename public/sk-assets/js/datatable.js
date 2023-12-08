


// DataTable @1.1
NioApp.DataTable = function (elm, opt) {
    if ($(elm).exists()) {
        $(elm).each(function () {
            var auto_responsive = $(this).data('auto-responsive'), has_export = (typeof (opt.buttons) !== 'undefined' && opt.buttons) ? true : false;
            var export_title = $(this).data('export-title') ? $(this).data('export-title') : 'Export';
            var btn = (has_export) ? '<"dt-export-buttons d-flex align-center"<"dt-export-title d-none d-md-inline-block">B>' : '', btn_cls = (has_export) ? ' with-export' : '';
            var dom_normal = '<"row justify-between g-2' + btn_cls + '"<"col-7 col-sm-4 text-start"f><"col-5 col-sm-8 text-end"<"datatable-filter"<"d-flex justify-content-end g-2"' + btn + 'l>>>><"datatable-wrap my-3"t><"row align-items-center"<"col-7 col-sm-12 col-md-9"p><"col-5 col-sm-12 col-md-3 text-start text-md-end"i>>';
            var dom_separate = '<"row justify-between g-2' + btn_cls + '"<"col-7 col-sm-4 text-start"f><"col-5 col-sm-8 text-end"<"datatable-filter"<"d-flex justify-content-end g-2"' + btn + 'l>>>><"my-3"t><"row align-items-center"<"col-7 col-sm-12 col-md-9"p><"col-5 col-sm-12 col-md-3 text-start text-md-end"i>>';
            var dom = $(this).hasClass('is-separate') ? dom_separate : dom_normal;
            var def = {
                    responsive: true,
                    autoWidth: false,
                    dom: dom,
                    language: {
                        search: "",
                        searchPlaceholder: "Type in to Search",
                        lengthMenu: "<span class='d-none d-sm-inline-block'>Show</span><div class='form-control-select'> _MENU_ </div>",
                        info: "_START_ -_END_ of _TOTAL_",
                        infoEmpty: "0",
                        infoFiltered: "( Total _MAX_  )",
                        paginate: {
                            "first": "First",
                            "last": "Last",
                            "next": "Next",
                            "previous": "Prev"
                        },
                    }
                },
                attr = (opt) ? extend(def, opt) : def;
            attr = (auto_responsive === false) ? extend(attr, { responsive: false }) : attr;

            $(this).DataTable(attr);
            $('.dt-export-title').text(export_title);
        });
    }
};

// DataTable Init @v1.0
NioApp.DataTable.init = function () {
    NioApp.DataTable('.datatable-init', {
        responsive: {
            details: true
        }
    });

    NioApp.DataTable('.datatable-init-export', {
        responsive: {
            details: true
        },
        buttons: ['copy', 'excel', 'csv', 'pdf']
    });
    $.fn.DataTable.ext.pager.numbers_length = 7;
}

