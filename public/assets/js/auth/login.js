/**
 * ============================================================
 * PAGE: Login
 * API : POST /api/v1/auth/login
 *
 * Sukses login:
 *  - API mengembalikan accessToken & refreshToken terenkripsi
 *    (dan juga men-set cookie via Set-Cookie).
 *  - Token disimpan ulang ke cookie __ajk-tib-at / __ajk-tib-rt
 *    dari sisi JS agar tetap tersimpan walau Set-Cookie server
 *    ditolak browser (flag Secure di environment http lokal).
 *  - Redirect ke dashboard client.
 * ============================================================
 */

(function () {

    function setCookie(name, value, minutes) {
        const expires = new Date(Date.now() + minutes * 60 * 1000).toUTCString();
        document.cookie = `${name}=${encodeURIComponent(value)}; expires=${expires}; path=/; SameSite=Lax`;
    }

    /* ── Toggle lihat password ── */
    $('.toggle-password').on('click', function () {
        const input = $('#password');
        const isText = input.attr('type') === 'text';
        input.attr('type', isText ? 'password' : 'text');
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });

    /* ── Submit login ── */
    $('#loginForm').on('submit', async function (e) {
        e.preventDefault();

        const username = $('#username').val().trim();
        const password = $('#password').val();
        const btn = $('#btn-submit');

        btn.prop('disabled', true).text('Memproses...');

        try {
            const res = await fetch('/api/v1/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ username, password })
            });

            const json = await res.json();

            if (!res.ok) {
                Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal',
                    text: json.message || 'Username atau password salah',
                    confirmButtonColor: '#00a651'
                });
                return;
            }

            // Simpan token ke cookie (at 24 jam, rt 30 hari)
            setCookie('__ajk-tib-at', json.data.accessToken, 1440);
            setCookie('__ajk-tib-rt', json.data.refreshToken, 43200);

            await Swal.fire({
                icon: 'success',
                title: 'Login Berhasil',
                text: 'Selamat datang kembali!',
                timer: 1200,
                showConfirmButton: false
            });

            window.location.href = '/client/dashboard';
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: 'Tidak dapat terhubung ke server. Silakan coba lagi.',
                confirmButtonColor: '#00a651'
            });
        } finally {
            btn.prop('disabled', false).text('Masuk');
        }
    });

})();
