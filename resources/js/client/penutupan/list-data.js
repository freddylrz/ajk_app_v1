/**
 * ============================================================
 * PAGE: Penutupan — Dalam Proses (type=1)
 * API  : GET /api/v1/client/declaration/list?type=1
 * ============================================================
 */

import { ClientHelper } from '../shared/helpers.js';
let firstPath = window.location.pathname
    .split('/')
    .filter(Boolean)[0];

$(function () {
    if(firstPath == 'tib'){
        firstPath = 'admin'
    }
    ClientHelper.renderDeclarationTable('#table-penutupan', 1, firstPath);
});
