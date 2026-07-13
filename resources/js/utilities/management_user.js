import {
    initializeDatepickers,
    initializeSelect2,
    initializeMoneyMask,
    showGlobalLoading,
    hideGlobalLoading,
    initTooltips
} from '../initialize.js';


$(document).ready(async function () {
    try {
        showGlobalLoading();
        $("#password, #confirm_password").keyup(checkPasswordMatch);
        $("#npassword, #newPassword").keyup(newcheckPasswordMatch);
        await getList();
        hideGlobalLoading();
    } catch (e) {
        console.error('Unexpected error on page load:', e);
    } finally {
    }
});

var statusAsset = false
$(document).on('click', '#btnAddUser', async function () {
    if (!statusAsset) {
        await getAsset();
        $('#branch_id').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#modal-add')
        });
    }
    $('#modal-add').modal('show')
});

function getAsset(payload) {
    return new Promise((resolve, reject) => {
        $.ajax({
            "url": '/api/v1/admin/utility/user/asset',
            "method": "GET",
            "timeout": 0,
            "data": payload
        }).done(async function (response) {
            renderAsset(response.data)
            resolve(response);  // Resolve the response when the AJAX request is successful
        }).fail(function (jqXHR, textStatus, errorThrown) {
            reject(errorThrown);  // Reject if there's an error
        });
    });
}

function renderAsset(response) {
    $('#role_id').html($('<option>', {
        value: '',
        text: "Pilih Role"
    }));

    $.each(response.role, function (i, item) {
        $('#role_id').append($('<option>', {
            value: item.role_id,
            text: item.role_name
        }));
    });

    $.each(response.branch, function (i, item) {
        $('#branch_id').append($('<option>', {
            value: item.branch_id,
            text: item.branch_name
        }));
    });
}

$(document).on('change', '#sub_role_id', function () {
    if ($(this).val() != 2) {
        $('#divCabang').show()
    } else {
        $('#divCabang').hide()
    }
});
$(document).on('change', '#role_id', function () {

    const showCabang = ['3', '4'].includes($(this).val());

    $('#divCabang').toggle(showCabang);
    $('#branch_id').prop('required', showCabang);

    if (!showCabang) {
        $('#branch_id').val([]).trigger('change');
    }

});

function getList() {
    return new Promise((resolve, reject) => {

        $.ajax({
            "url": '/api/v1/admin/utility/user/list',
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
                "data": response.data.list,
                "columns": [
                    {
                        "data": "user_id",
                        className: "text-center",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { "data": "username" },
                    {
                        "data": "branch_name",
                        defaultContent: "-",
                    },
                    {
                        "data": "is_active",
                        className: "text-center",
                        render: function (data, type, row, meta) {
                            let badge = 'bg-success'

                            if (data == 0) {
                                badge = 'bg-danger'
                            }
                            return `<span class="badge f-14 ${badge}">${row.is_active_desc}</span>
                               `;
                        }
                    },
                    {
                        "data": "created_at",
                        className: "text-center",

                    },
                    {
                        "data": "user_id",
                        className: "text-center",
                        render: function (data, type, row, meta) {
                            return `<button class="btn btn-warning btn-sm btnEdit" type="button" 
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Edit User"
                        data-id="${data}" id="edit" style="margin-right: 10px">
                        <i class="fas fa-pencil-alt"></i></button>

                        <button class="btn btn-danger btn-sm btnDelete"  
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Hapus User"
                        type="button" data-id="${data}" id="delete" style="margin-right: 10px">
                        <i class="fa fa-trash"></i></button>
                        `;
                        }
                    },
                ],
            });
            initTooltips();
        })
        resolve();
    })
}


$(document).on('click', '.btnEdit', function () {
    openModalResetPassword($(this).data('id'))
})

$(document).on('click', '.btnDelete', function () {
    updateDeleteUser(0, $(this).data('id'))
})

$('#saveEdit').on('click', function () {
    if (!$('#newPassword').val()) {
        Swal.fire({
            icon: 'warning',
            text: "Harap isi password baru!",
            showConfirmButton: true,
            allowOutsideClick: false,
        });
    } else {
        updateDeleteUser(1)
    }
})

$("#formUser").on("submit", async function (e) {
    e.preventDefault();
    // const url = '/api/v1/admin/utility/user/update'

    // if(id == 0){
        // url = '/api/v1/admin/utility/user/insert'
    // }
    if (!checkPass) {
        await Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Password tidak sesuai.',
            allowOutsideClick: false,
        });
        return;
    }

    const confirm = await Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin menyimpan data user ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Simpan',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        allowOutsideClick: false
    });

    if (!confirm.isConfirmed) {
        return;
    }

    Swal.fire({
        icon: 'info',
        text: 'Loading...',
        showConfirmButton: false,
        allowOutsideClick: false,
    });

    var data = JSON.stringify(
        {
            "display_name": $('#display').val(),
            "username": $('#name').val(),
            "role_ids": [$('#role_id').val()],
            "is_active": 1,
            "branch_ids": $('#branch_id').val() || [],
            "password": $('#confirm_password').val(),
        })

    $.ajax({
        "url": '/api/v1/admin/utility/user/insert',
        "method": "POST",
        "timeout": 0,
        "data": data,
        success: function (response) {
            Swal.fire({
                icon: 'success',
                text: "berhasil!",
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

function openModalResetPassword(id) {
    $('#modal-edit').modal('show')
    $('#userId').val(id)
}

async function updateDeleteUser(stat = 0, id = 0) {
    let dataPayload = {};
    const url = '/api/v1/admin/utility/user/update'
    if (stat === 0) { // Untuk operasi hapus
        url = '/api/v1/admin/utility/user/delete'

        const result = await Swal.fire({
            title: "Apakah anda yakin?",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: "Hapus"
        });

        if (!result.isConfirmed) return;

        dataPayload = {
            userId: id
        };

    } else { // Untuk operasi edit
        Swal.fire({
            icon: 'info',
            text: "Loading!",
            showConfirmButton: false,
            allowOutsideClick: false,
        });

        dataPayload = JSON.stringify(
            {
                "display_name": $('#display').val(),
                "username": $('#name').val(),
                "role_ids": [$('#role_id').val()],
                "is_active": 1,
                "branch_ids": $('#branch_id').val() || [],
                "password": $('#confirm_password').val(),
            })
    }

    try {
        await sendRequest(encryptedData,url, stat === 0 ? "Hapus berhasil!" : "Edit berhasil!");
        Swal.fire({
            icon: 'success',
            title: stat === 0 ? 'Hapus berhasil!' : 'Edit berhasil!',
            showConfirmButton: false,
            timer: 2000
        });
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: stat === 0 ? 'Hapus gagal!' : 'Edit gagal!',
            text: error.message || 'Terjadi kesalahan!',
            showConfirmButton: false,
            timer: 2000
        });
    }
    // setTimeout(() => location.reload(), 2000);

}

async function sendRequest(data, url, successMessage) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: base_url.concat(url),
            method: "POST",
            timeout: 0,
            headers: {
                "Authorization": "Bearer " + token
            },
            data: { data: data },
            success: function (response) {
                resolve(response);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                let errorMessage = 'Request failed';
                if (jqXHR.status === 500) {
                    errorMessage = 'Server error (500)';
                } else if (jqXHR.status === 404) {
                    errorMessage = 'API endpoint tidak ditemukan (404)';
                }
                reject(new Error(errorMessage));
            }
        });
    });
}

const togglePassword = document.querySelector("#togglePassword");
const newtogglePassword = document.querySelector("#newtogglePassword");
const password = document.querySelector("#password");
const newpassword = document.querySelector("#npassword");

togglePassword.addEventListener("click", function () {
    // toggle the type attribute
    const type = password.getAttribute("type") === "password" ? "text" : "password";
    password.setAttribute("type", type);

    // toggle the icon
    this.classList.toggle("fa-eye-slash");
});
newtogglePassword.addEventListener("click", function () {
    // toggle the type attribute
    const type = newpassword.getAttribute("type") === "password" ? "text" : "password";
    newpassword.setAttribute("type", type);

    // toggle the icon
    this.classList.toggle("fa-eye-slash");
});

var checkPass = false
function checkPasswordMatch() {
    var password = $("#password").val();
    var confirmPassword = $("#confirm_password").val();

    if (password != confirmPassword) {
        checkPass = false;
        $("#divCheckPasswordMatch").html("<p style='color: red'>Password Tidak Sama!</p>");
    }
    else {
        checkPass = true;
        $("#divCheckPasswordMatch").html("<p style='color: green'>Password Sama!</p>");
    }
}
function newcheckPasswordMatch() {
    var password = $("#npassword").val();
    var confirmPassword = $("#newPassword").val();

    if (password != confirmPassword) {
        $("#divNewCheckPasswordMatch").html("<p style='color: red'>Password Tidak Sama!</p>");
        checkPass = false;
    }
    else {
        checkPass = true;
        $("#divNewCheckPasswordMatch").html("<p style='color: green'>Password Sama!</p>");
    }
}


