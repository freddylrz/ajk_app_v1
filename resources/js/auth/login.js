import {
    getAccessTokenFromCookies
} from '/resources/js/helper_cookie.js';
let searchParams = new URLSearchParams(window.location.search);

const redirectUrl = searchParams.get('redirect_url');

$(document).ready(() => {
    $('#loginForm').on('submit', handleLogin);
    $('.toggle-password').on('click', togglePassword);
});

// Helper function to set cookies with expiration
function setCookie(name, value, expireDate) {
    const date = new Date(expireDate);
    const expires = `; expires=${date.toUTCString()}`;
    document.cookie = `${name}=${value}${expires}; domain=${domain}; path=/;`;
}

// Function to handle login process
async function handleLogin(event) {
    event.preventDefault();

    const username = $('#username').val();
    const password = $('#password').val();

    if (!username) {
        return showAlert('error', 'Mohon masukan username Anda terlebih dahulu!');
    }

    if (!password) {
        return showAlert('error', 'Mohon masukan password Anda terlebih dahulu!');
    }

    const loginButton = $('#btn-submit');
    loginButton.data('original-text', loginButton.html());
    setLoading(loginButton, true);

    try {
        const dataLogin = JSON.stringify({ username, password });
        // const encryptedLogin = await encryptData(dataLogin, 1);

        const response = await $.ajax({
            url: `${authBackendUrl}/api/v1/auth/login`,
            method: 'POST',
            headers: {
                "X-Timestamp": Math.floor(Date.now() / 1000),
                "Content-Type": "application/json"
            },
            data: dataLogin
        });

        setLoading(loginButton, false);

        if (response.data) {
            
            setTimeout(async () => {
                const newToken = await getAccessTokenFromCookies();
                $.ajax({
                    url: `${authBackendUrl}/api/v1/auth/user-info`,
                    method: "GET",
                    timeout: 0,
                    headers: {
                        "X-Timestamp": Math.floor(Date.now() / 1000),
                        'Authorization': 'Bearer ' + newToken,
                    },
                }).done(async function (responses) {
                    const roles = responses.data.user_info.roles || [];

                    Swal.fire({
                        icon: 'success',
                        title: "Berhasil Masuk",
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        timer: 1000,
                    })

                    if (roles.includes('OPR') || roles.includes('SPV')) {
                        setTimeout(() => {
                            window.location.href = redirectUrl || '/client/dashboard';
                        }, 1000);
                    } else {
                        setTimeout(() => {
                            window.location.href = redirectUrl || '/tib/dashboard';
                        }, 1000);
                    }
                });
            }, 1000);

        } else {
            showAlert('warning', response.message);
        }
    } catch (error) {
        setLoading(loginButton, false);
        const message = error.responseJSON?.message || 'An unexpected error occurred';
        showAlert('error', message);
    }
}

// Function to toggle password visibility
function togglePassword(event) {
    event.preventDefault();

    const passwordField = document.getElementById("password");
    const toggleIcon = document.querySelector(".toggle-password i");

    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleIcon.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        passwordField.type = "password";
        toggleIcon.classList.replace("fa-eye-slash", "fa-eye");
    }
}

// Function to toggle loading state on a button
function setLoading(button, isLoading) {
    if (isLoading) {
        button.html('<i class="spinner-border spinner-border-sm"></i> Proses...');
        button.prop('disabled', true);
    } else {
        button.html(button.data('original-text'));
        button.prop('disabled', false);
    }
}

// Function to show alert
function showAlert(type, message) {
    Swal.fire({
        icon: type,
        title: message,
        showConfirmButton: true,
        allowOutsideClick: false
    });
}
