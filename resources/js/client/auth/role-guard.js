import { ClientHelper } from '../shared/helpers.js';

$(async function () {
    try {
        const roles = await ClientHelper.getRoles();

        if (roles.includes('SPV')) {
            $('#menu-client-input-data').remove();
            $('#btn-input-data-debitur').remove();
        }
    } catch (err) {
        console.error('Gagal memuat peran user:', err);
    }
});
