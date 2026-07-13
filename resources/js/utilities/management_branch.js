import {
    initializeDatepickers,
    initializeSelect2,
    initializeMoneyMask,
    showGlobalLoading,
    hideGlobalLoading,
    initTooltips
} from '../initialize.js';

$(document).ready(async function () {
    showGlobalLoading();

    try {
        await getList();
    } catch (e) {
        console.error('Unexpected error on page load:', e);
    } finally {
        hideGlobalLoading();
    }
});

var statusAsset = false
$(document).on('click', '#btnAddUser', async function () {
    $('#branch_name').val('')
    $('#modal-add').modal('show')
});

function getList() {
    $.ajax({
        "url": '/api/v1/admin/utility/branch/list',
        "method": "GET",
        "timeout": 0,
    }).done(async function (response) {
        $('#table').DataTable({
            "processing": false,
            "pageLength": 25,
            "autoWidth": false,
            "order": [],
            "bDestroy": true,
            "searching": false,
            "data": response.data,
            "columns": [
                {
                    "data": "branch_id",
                    className: 'text-center',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { "data": "branch_name" },
                {
                    "data": "created_at",
                    className: 'text-center'
                },
                {
                    "data": "branch_id",
                    className: "text-center",
                    render: function (data, type, row, meta) {
                        return `
                            <button
                                class="btn btn-warning btn-sm btnEdit"
                                type="button"
                                data-id="${data}"
                                data-name="${row.branch_name}"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Edit Cabang"
                                style="margin-right:10px">
                                <i class="fas fa-pencil-alt"></i>
                            </button>

                            <button
                                class="btn btn-danger btn-sm btnDelete"
                                type="button"
                                data-id="${data}"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Hapus Cabang">
                                <i class="fa fa-trash"></i>
                            </button>
                        `;
                    }
                },
            ],
        });
        initTooltips();

    })
}


$(document).on('click', '.btnEdit', function () {
    $('#branch_name').val($(this).data('name'))
    $('#branch_id').val($(this).data('id'))
    $('#modal-add').modal('show')
})

$(document).on('click', '.btnDelete', function () {
    deleteBranch($(this).data('id'))
})

$("#formBranch").on("submit", async function (e) {
    e.preventDefault();
    Swal.fire({
        icon: 'info',
        text: "Loading!",
        showConfirmButton: false,
        allowOutsideClick: false,
    });

    const branch_id = $('#branch_id').val() || 0
    let url = '/api/v1/admin/utility/branch/update'

    if (branch_id == 0) {
        url = '/api/v1/admin/utility/branch/insert'
    }

    var data = JSON.stringify({
        "branch_id": branch_id,
        // 0 -> insert;
        "branch_name": $('#branch_name').val()
    })
    $.ajax({
        "url": url,
        "method": "POST",
        "timeout": 0,
        "data": data,
        success: function (response) {
            Swal.fire({
                icon: 'success',
                text: "Input berhasil!",
                showConfirmButton: false,
                timer: 2000
            });

            setInterval(function () {
                location.reload();
            }, 1000);
        },
        error: function (error) {
            var data = error.responseJSON; // Ambil data error dari responseJSON

            let errorMessage = 'Terjadi kesalahan! Silahkan coba lagi.'; // Pesan default

            // Cek jika ada pesan error spesifik dari server
            if (data && data.errors) {
                // Ambil setiap pesan error dari fields dan gabungkan menjadi satu string
                errorMessage = Object.values(data.errors)
                    .map(messages => messages.join('<br>'))
                    .join('<br>'); // Gabungkan pesan dari setiap field dengan line break
            } else if (data && data.message) {
                errorMessage = data.message; // Gunakan pesan umum jika tidak ada detail error
            }

            Swal.fire({
                icon: 'error',
                html: errorMessage, // Menampilkan pesan error dari setiap field
                showConfirmButton: true,
                allowOutsideClick: false
            });
        }
    })
});

async function deleteBranch(id = 0) {

    const result = await Swal.fire({
        title: "Apakah anda yakin?",
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: "Hapus"
    });

    if (!result.isConfirmed) return;

    $.ajax({
        "url": '/api/v1/admin/utility/branch/delete',
        "method": "POST",
        "timeout": 0,
        "data": JSON.stringify({
            branch_id: id
        }),
        success: function (response) {
            Swal.fire({
                icon: 'success',
                text: "Hapus berhasil!",
                showConfirmButton: false,
                timer: 2000
            });

            setInterval(function () {
                location.reload();
            }, 1000);
        },
        error: function (error) {
            var data = error.responseJSON; // Ambil data error dari responseJSON

            let errorMessage = 'Terjadi kesalahan! Silahkan coba lagi.'; // Pesan default

            // Cek jika ada pesan error spesifik dari server
            if (data && data.errors) {
                // Ambil setiap pesan error dari fields dan gabungkan menjadi satu string
                errorMessage = Object.values(data.errors)
                    .map(messages => messages.join('<br>'))
                    .join('<br>'); // Gabungkan pesan dari setiap field dengan line break
            } else if (data && data.message) {
                errorMessage = data.message; // Gunakan pesan umum jika tidak ada detail error
            }

            Swal.fire({
                icon: 'error',
                html: errorMessage, // Menampilkan pesan error dari setiap field
                showConfirmButton: true,
                allowOutsideClick: false
            });
        }
    });
}


