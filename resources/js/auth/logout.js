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

        $('.displayName').text(responses.data.user_info.display_name || 'User');
        function isActive(urls) {
            return urls.some(url => path.startsWith(url)) ? 'active' : '';
        }

        let menuHtml = '';

        if (roles.includes('SA')) {
            $('.menuTIB, .menuBPR').show();
        }

        if (roles.includes('OPR')) {
            $('.menuBPR, .menuOPR').show();
        }

        if (roles.some(r => ['MKT', 'SPV'].includes(r))) {
            $('.menuBPR').show();
        }

        // inject sekali aja (lebih efisien)
        $('#pc-navbar-menu').append(menuHtml);

    });
}
// Call this function when the document is ready or on a specific event
$(document).ready(async function () {
    await getUserInfo()

    $('#logout-button').click(function () {
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
            clearCookies();

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
function clearCookies() {
    const domain = import.meta.env.VITE_DOMAIN
    const cookieName = "__tib-rt";

    // Clear the cookie for the specified domain
    document.cookie = `${cookieName}=;expires=Thu, 01 Jan 1970 00:00:00 UTC; domain=${domain}; path=/;`;

    // Clear the cookie for the current subdomain (if needed)
    const currentDomain = window.location.hostname;
    document.cookie = `${cookieName}=;expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
}
