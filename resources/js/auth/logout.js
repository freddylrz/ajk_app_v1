import {
    getAccessTokenFromCookies,
    getRefreshAccessTokenFromCookies
} from '/resources/js/helper_cookie.js';
var token
window.addEventListener("unhandledrejection", function (e) {
    console.error("🚨 Unhandled Promise rejection:", e.reason);
});
$(document).ajaxError(function (event, jqxhr, settings, thrownError) {
    console.error('🌐 AJAX ERROR:', thrownError, jqxhr.responseText);
});

(function ($) {
    if (!$) return;

    const originalAjax = $.ajax;
    let refreshInProgress = null;

    const baseDomain = import.meta.env.VITE_BASE_API_URL || '';

    $.ajaxPrefilter(function (options) {
        if (options.url && !/^https?:\/\//i.test(options.url)) {
            options.url = baseDomain.replace(/\/$/, '') + '/' + options.url.replace(/^\//, '');
        }
    });

    async function refreshToken() {

        const token = await getRefreshAccessTokenFromCookies();
        const url = '/api/v1/auth/refresh';

        if (!token) {
            throw new Error('Refresh token tidak ada');
        }

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token,
                'x-timestamp': Math.floor(Date.now() / 1000)
            }
        });

        if (!response.ok) {
            throw new Error('Refresh token gagal: ' + response.status);
        }

        return response.json();
    }

    async function ensureRefresh() {

        if (!refreshInProgress) {
            console.log('🔁 Refreshing token...');

            refreshInProgress = (async () => {
                try {
                    await refreshToken();
                } finally {
                    refreshInProgress = null;
                }
            })();
        }

        return refreshInProgress;
    }

    $.ajax = function (settings) {

        const deferred = $.Deferred();
        let realJqXHR = null;

        (async () => {

            try {

                let accessToken = await getAccessTokenFromCookies();

                // ==============================
                // 1️⃣ Jika access token tidak ada
                // ==============================

                if (!accessToken) {

                    console.log('⚠️ Access token tidak ada, refresh dulu');

                    await ensureRefresh();

                    accessToken = await getAccessTokenFromCookies();

                    if (!accessToken) {
                        return deferred.reject('Token tetap kosong setelah refresh');
                    }
                }

                settings.headers = settings.headers || {};
                settings.headers['Authorization'] = 'Bearer ' + accessToken;
                settings.headers['Content-Type'] = "application/json";
                settings.headers['x-timestamp'] = Math.floor(Date.now() / 1000);

                realJqXHR = originalAjax(settings);

                realJqXHR.done((data, textStatus, jqXHR) => {
                    deferred.resolve(data, textStatus, jqXHR);
                });

                realJqXHR.fail(async (jqXHR, textStatus, errorThrown) => {

                    // ==============================
                    // 2️⃣ Jika bukan 401
                    // ==============================

                    if (jqXHR.status !== 401) {
                        return deferred.reject(jqXHR, textStatus, errorThrown);
                    }

                    try {

                        console.log('🔁 Token expired, refreshing...');

                        await ensureRefresh();

                        const newToken = await getAccessTokenFromCookies();

                        if (!newToken) {
                            return deferred.reject('Token kosong setelah refresh');
                        }

                        console.log('✅ Token refreshed, retrying request');

                        settings.headers['Authorization'] = 'Bearer ' + newToken;
                        settings.headers['Content-Type'] = "application/json";
                        settings.headers['x-timestamp'] = Math.floor(Date.now() / 1000);

                        realJqXHR = originalAjax(settings);

                        realJqXHR.done((data, textStatus, jqXHR) => {
                            deferred.resolve(data, textStatus, jqXHR);
                        });

                        realJqXHR.fail((jqXHR, textStatus, errorThrown) => {
                            deferred.reject(jqXHR, textStatus, errorThrown);
                        });

                    } catch (err) {
                        deferred.reject(err);
                    }

                });

            } catch (err) {
                deferred.reject(err);
            }

        })();

        const combined = deferred.promise();

        return Object.assign(combined, {
            abort: () => realJqXHR?.abort()
        });
    };

})(window.jQuery);

export var roles;
async function getUserInfo() {
    $.ajax({
        url: '/api/v1/auth/user-info',
        method: "GET",
        timeout: 0,
    }).done(async function (responses) {

        roles = responses.data.user_info.roles || [];
        const path = window.location.pathname;

        $('.display_user').text(responses.data.user_info.display_name || 'User');

    });
}
// Call this function when the document is ready or on a specific event
$(document).ready(async function () {
    await getUserInfo()

    $('#logout').click(function () {
        // Show loading alert
        Swal.fire({
            title: 'Sedang proses keluar...',
            text: 'Mohon tunggu sebentar.',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.fire({
                    title: 'Proses...',
                    allowOutsideClick: false, // Disable clicking outside
                    didOpen: () => {
                        // Show loading spinner
                        Swal.showLoading();
                    }
                });
                // Show loading spinner
            }
        });

        // Simulate an async operation (clearing cookies)
        setTimeout(function () {
            // Clear cookies
            clearCookie('__ajk-tib-rt');

            // Close loading alert
            Swal.close();

            // Show success alert
            Swal.fire({
                icon: 'success',
                title: 'Berhasil keluar!',
                timer: 2000,
                showConfirmButton: false,
                allowOutsideClick: false,
            }).then(() => {
                // Redirect to the login page after the success alert
                window.location.href = "/";
            });

        }, 2000); // Simulate delay for clearing cookies
    });

});

// Function to clear the 'access_token' cookie for the specified domain and subdomains
function clearCookie(name) {
    const hostname = window.location.hostname;

    // Semua kemungkinan domain
    const domains = new Set([
        "",
        hostname,
        "." + hostname,
    ]);

    // Tambahkan parent domain
    const parts = hostname.split(".");
    for (let i = 0; i < parts.length - 1; i++) {
        const domain = parts.slice(i).join(".");
        domains.add(domain);
        domains.add("." + domain);
    }

    // Hapus cookie di semua kombinasi domain
    domains.forEach(domain => {
        const domainPart = domain ? `; domain=${domain}` : "";

        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/${domainPart}`;
        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/; SameSite=Lax${domainPart}`;
        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/; SameSite=None; Secure${domainPart}`;
    });
}
