/**
 * Global DataTables Configuration for Koperasi System
 * Provides responsive tables with print/export functionality
 */

// Default configuration for DataTables
window.defaultDataTableConfig = {
    responsive: true,
    processing: true,
    serverSide: true,
    dom: 'Bfrtip',
    buttons: [
        {
            extend: 'print',
            title: function() {
                return document.title;
            },
            text: '<i class="fas fa-print"></i> Cetak',
            className: 'btn btn-primary btn-sm'
        },
        {
            extend: 'excel',
            title: function() {
                return document.title;
            },
            text: '<i class="fas fa-file-excel"></i> Excel',
            className: 'btn btn-success btn-sm'
        },
        {
            extend: 'pdf',
            title: function() {
                return document.title;
            },
            text: '<i class="fas fa-file-pdf"></i> PDF',
            className: 'btn btn-danger btn-sm'
        }
    ],
    lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, 'Semua']
    ],
    language: {
        search: "Cari:",
        lengthMenu: "Tampilkan _MENU_ entri",
        info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
        infoEmpty: "Menampilkan 0 hingga 0 dari 0 entri",
        infoFiltered: "(disaring dari _MAX_ total entri)",
        loadingRecords: "Memuat...",
        processing: "Memproses...",
        zeroRecords: "Tidak ada data yang ditemukan",
        emptyTable: "Tidak ada data tersedia dalam tabel",
        paginate: {
            first: "Pertama",
            last: "Terakhir",
            next: "<i class='fas fa-chevron-right'></i>",
            previous: "<i class='fas fa-chevron-left'></i>"
        },
        buttons: {
            print: "Cetak",
            excel: "Excel",
            pdf: "PDF"
        }
    }
};

// Helper function to initialize DataTable with default config
window.initResponsiveDataTable = function(selector, customConfig = {}) {
    const config = $.extend(true, {}, window.defaultDataTableConfig, customConfig);
    return $(selector).DataTable(config);
};

// Setup CSRF token for all AJAX requests
$(document).ready(function() {
    // Set up CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

// Helper function for report tables (with date filtering)
window.initReportDataTable = function(selector, ajaxUrl, columns, customConfig = {}) {
    const config = $.extend(true, {}, window.defaultDataTableConfig, {
        ajax: {
            url: ajaxUrl,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: function(d) {
                if ($('#start_date').length) {
                    d.start_date = $('#start_date').val();
                }
                if ($('#end_date').length) {
                    d.end_date = $('#end_date').val();
                }
                if ($('#anggota_id').length) {
                    d.anggota_id = $('#anggota_id').val();
                }
            }
        },
        columns: columns
    }, customConfig);

    return $(selector).DataTable(config);
};
