/**
 * ============================================================
 * PAGE: Penutupan — Terbit Polis (type=2)
 * API  : GET /api/v1/client/declaration/list?type=2
 * ============================================================
 */

import { ClientHelper } from '../shared/helpers.js';
let firstPath = window.location.pathname
    .split('/')
    .filter(Boolean)[0];

$(function () {
    if (firstPath == 'tib') {
        firstPath = 'admin'
    }
    ClientHelper.renderDeclarationTable('#table-terbit-polis', 2, firstPath);
});
