/**
 * ============================================================
 * PAGE: Penutupan — Terbit Polis (type=2)
 * API  : GET /api/v1/client/declaration/list?type=2
 * ============================================================
 */

import { ClientHelper } from './helpers.js';

$(function () {
    ClientHelper.renderDeclarationTable('#table-terbit-polis', 2);
});
