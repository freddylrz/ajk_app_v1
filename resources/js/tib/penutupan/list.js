import {
    initializeDatepickers,
    initializeSelect2,
    initializeMoneyMask,
    showGlobalLoading,
    hideGlobalLoading,
    initTooltips
} from '../../initialize.js';

const lastPath = window.location.pathname
    .split('/')
    .filter(Boolean)
    .pop();

$(document).ready(async function () {
    let type = 0;
    $('#pageTitle').text('List Terbit Polis')
    try {
        if(lastPath == 'list-data'){
            $('#pageTitle').text('List Penutupan')
            type = 1;
        }
        showGlobalLoading();
        await getList(type);
        hideGlobalLoading();
    } catch (e) {
        console.error('Unexpected error on page load:', e);
    } finally {
    }
});

function getList(type){
    if ($.fn.DataTable.isDataTable('#table-penutupan')) {
        $('#table-penutupan').DataTable().destroy();
    }

    $('#table-penutupan').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        searching: true,
        autoWidth: false,

        ajax: async function (data, callback) {
            data.type = type;
            Swal.fire({
                icon: 'info',
                text: 'Memuat data..!',
                showConfirmButton: false,
                allowOutsideClick: false,
            });

            $.ajax({
                url: "/api/v1/admin/declaration/list",
                type: "GET",
                data: data,
                traditional: false,
                dataType: "json",
                success: function (response) {
                    callback({
                        draw: data.draw,
                        recordsTotal: response.pagination.total,
                        recordsFiltered: response.pagination.total,
                        data: response.data
                    });
                },
                error: function (xhr) {
                    console.error(xhr);

                    callback({
                        draw: data.draw,
                        recordsTotal: 0,
                        recordsFiltered: 0,
                        data: []
                    });

                    Swal.fire({
                        icon: "error",
                        text: xhr.responseJSON?.message || xhr.statusText || "Gagal mengambil data"
                    });
                },
                complete: function () {
                    Swal.close();
                }
            });
        },

        columns: [
            {
                data: "id",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'declaration_no' },
            { data: 'insured_name' },
            { data: 'nik' },
            { data: 'birth_date' },
            { data: 'gender_desc' },
            { data: 'branch_name' },
            { data: 'plafond' },
            {
                "data": "status_name",
                className: "text-center",
                render: function (data, type, row, meta) {
                    return `<a class="btn btn-warning btn-sm btnDetail"
                        href="/tib/penutupan/detail?userId=${row.id}">${data}</i></a>`;
                }
            },
        ],

        pageLength: 25,
        order: [[0, 'desc']],
        initComplete: function () {
            const table = this.api();

            const input = $('#table-penutupan_filter input');

            input.off();

            input.on('change keypress', function (e) {
                if (e.type === 'change' || e.which === 13) {
                    table.search(this.value).draw();
                }
            });
        }
    });
}