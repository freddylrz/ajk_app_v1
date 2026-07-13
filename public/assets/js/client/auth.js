/**
 * ============================================================
 * AUTH — Area Client (jalan di semua halaman client)
 * API : GET  /api/v1/auth/user-info  → tampilkan nama user & role
 *       POST /api/v1/auth/refresh    → perpanjang sesi bila access token kadaluarsa
 *       POST /api/v1/auth/logout     → akhiri sesi
 *
 * Token tersimpan terenkripsi di cookie:
 *   __ajk-tib-at = access token (24 jam)
 *   __ajk-tib-rt = refresh token (30 hari)
 * Token dikirim apa adanya sebagai Bearer — dekripsi dilakukan
 * middleware DecryptSanctumToken di sisi server.
 * ============================================================
 */

const ClientAuth = {

    /* ── Util cookie ── */
    getCookie(name) {
        const match = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
        return match ? decodeURIComponent(match[1]) : null;
    },

    setCookie(name, value, minutes) {
        const expires = new Date(Date.now() + minutes * 60 * 1000).toUTCString();
        document.cookie = `${name}=${encodeURIComponent(value)}; expires=${expires}; path=/; SameSite=Lax`;
    },

    clearTokens() {
        document.cookie = '__ajk-tib-at=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/';
        document.cookie = '__ajk-tib-rt=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/';
    },

    goToLogin() {
        this.clearTokens();
        window.location.href = '/login';
    },

    /* ── Request API ber-token ── */
    async apiFetch(url, options = {}, token = null) {
        const bearer = token || this.getCookie('__ajk-tib-at');
        const res = await fetch(url, {
            ...options,
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + bearer,
                ...(options.headers || {})
            }
        });
        return res;
    },

    /* ── Perpanjang sesi dengan refresh token ── */
    async refreshSession() {
        const rt = this.getCookie('__ajk-tib-rt');
        if (!rt) return false;

        try {
            const res = await this.apiFetch('/api/v1/auth/refresh', { method: 'POST' }, rt);
            if (!res.ok) return false;

            const json = await res.json();
            this.setCookie('__ajk-tib-at', json.data.accessToken, 1440);
            this.setCookie('__ajk-tib-rt', json.data.refreshToken, 43200);
            return true;
        } catch (err) {
            return false;
        }
    },

    /* ── Muat info user, refresh otomatis jika token kadaluarsa ── */
    async loadUserInfo(sudahRefresh = false) {
        try {
            const res = await this.apiFetch('/api/v1/auth/user-info');

            if (res.status === 401) {
                if (!sudahRefresh && await this.refreshSession()) {
                    return this.loadUserInfo(true);
                }
                this.goToLogin();
                return;
            }

            if (!res.ok) return; // error server: biarkan tampilan default

            const json = await res.json();
            const info = json.data.user_info;

            $('#display_user').text(info.display_name);
            $('#header_user').text(info.display_name);
        } catch (err) {
            // Jaringan bermasalah: jangan tendang user keluar, cukup diam
            console.error('Gagal memuat info user:', err);
        }
    },

    /* ── Logout ── */
    async logout() {
        try {
            await this.apiFetch('/api/v1/auth/logout', { method: 'POST' });
        } catch (err) {
            // Token dihapus lokal walaupun API gagal dihubungi
        }
        this.goToLogin();
    }
};

$(function () {
    // Tanpa access token & refresh token sama sekali → ke halaman login
    if (!ClientAuth.getCookie('__ajk-tib-at') && !ClientAuth.getCookie('__ajk-tib-rt')) {
        ClientAuth.goToLogin();
        return;
    }

    ClientAuth.loadUserInfo();

    $('#logout').on('click', function () {
        ClientAuth.logout();
    });
});
