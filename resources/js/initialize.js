import { roles as importedrole } from '/resources/js/auth/logout.js';

export async function waitForrole(timeout = 5000) {
    const start = Date.now();
    while (!importedrole) {
        // kalau lebih dari timeout 5 detik, keluar
        if (Date.now() - start > timeout) {
            throw new Error("role tidak tersedia dalam waktu yang wajar");
        }
        await new Promise(r => setTimeout(r, 500)); // tunggu 500ms
    }
    return importedrole;
}


export function initTooltips() {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        bootstrap.Tooltip.getOrCreateInstance(el);
    });
}

export function initializeDatepickers() {
    document.querySelectorAll('.datepicker').forEach(el => {
        new Datepicker(el, {
            buttonClass: 'btn',
            format: 'dd-mm-yyyy', // format D/M/Y
            autohide: true        // opsional: tutup otomatis setelah pilih tanggal
        });
    });
}

export function initializeSelect2() {
    $(".select2").select2({ theme: "bootstrap-5" });
}

export function initializeMoneyMask() {
    $('.uang').inputmask({
        alias: 'numeric',
        groupSeparator: ',',
        digits: 2,
        rightAlign: true,
        autoGroup: true,
        placeholder: '0'
    });
}

export function showGlobalLoading() {
    Swal.fire({
        title: 'Loading...',
        text: 'Please wait a moment.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

export function hideGlobalLoading() {
    Swal.close();
}

export function handleRedirect(statId, user, reqId, rules) {
    for (let rule of rules) {
        if (rule.statIds.includes(statId)) {
            if (!rule.roles) {
                // Jika tidak ada pembatasan role, langsung redirect
                window.location.href = `${rule.url}?reqId=${reqId}`;
                return true;
            }

            // Jika user adalah array
            if (Array.isArray(user)) {
                if (rule.roles.some(role => user.includes(role))) {
                    window.location.href = `${rule.url}?reqId=${reqId}`;
                    return true;
                }
            } else {
                // Jika user adalah string
                if (rule.roles.includes(user)) {
                    window.location.href = `${rule.url}?reqId=${reqId}`;
                    return true;
                }
            }
        }
    }
    return false;
}
