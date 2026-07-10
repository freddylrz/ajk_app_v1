<!DOCTYPE html>
<html lang="id">
<head>
    <title>@yield('pageTitle', 'TuguBro')</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="description" content="Asuransi Profesi Tenaga Medis dan Tenaga Kesehatan"/>
    <meta name="keywords" content="Asuransi, TuguBro, Broker Asuransi, Tenaga Kesehatan, Tenaga Medis"/>
    <meta name="author" content="TuguBro"/>

    <link rel="icon" href="{{ asset('assets/images/tib-logo.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/fonts/inter/inter.css') }}" id="main-font-link"/>
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/datepicker-bs5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link"/>
    <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/notifier.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/select.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/select2-bootstrap-5-theme.min.css') }}">
    @stack('levelPluginsJsHeader')

    <style>
        /* ─── Background ─── */
        .pc-container {
            background-color: #f1f1f1 !important;
        }
        .pc-container .pc-content {
            padding: 22px 20px 32px !important;
        }

        /* ─── Cards ─── */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.18);
            overflow: hidden;
            background: #fff;
            margin-bottom: 20px;
        }
        .card-header {
            background: #2d3748;
            color: #e2e8f0;
            padding: 13px 18px;
            border-bottom: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .card-header h3,
        .card-header h4,
        .card-header h5 {
            color: #e2e8f0 !important;
            font-size: 14.5px !important;
            font-weight: 600;
            margin: 0;
        }
        .card-header i {
            color: #93c5fd;
            font-size: 18px !important;
        }
        .card-body {
            padding: 18px;
            background: #fff;
        }

        /* ─── Stat cards (dashboard) ─── */
        .stat-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            margin-bottom: 6px;
        }
        .stat-value {
            font-size: 26px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1;
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .stat-icon i { font-size: 20px !important; }
        .bg-info-soft    { background: rgba(14,165,233,0.12); }
        .bg-success-soft { background: rgba(34,197,94,0.12); }
        .bg-primary-soft { background: rgba(99,102,241,0.12); }
        .bg-warning-soft { background: rgba(234,179,8,0.12); }

        /* ─── Offcanvas Filter ─── */
        .offcanvas {
            width: 340px !important;
        }
        .offcanvas-header {
            background: #2d3748;
            color: #e2e8f0;
            padding: 13px 18px;
            border-bottom: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .offcanvas-title {
            color: #e2e8f0 !important;
            font-size: 14.5px !important;
            font-weight: 600;
            margin: 0;
        }
        .offcanvas-header i {
            color: #93c5fd;
            font-size: 18px !important;
        }
        .offcanvas-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.7;
            margin-left: auto;
        }
        .offcanvas-footer {
            padding: 14px 18px;
            border-top: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        /* ─── Tables ─── */
        .table thead th {
            background: #f1f5f9;
            font-size: 13px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            white-space: nowrap;
            padding: 11px 13px;
            border-bottom: 2px solid #e2e8f0;
        }
        .table tbody td {
            font-size: 13.5px;
            vertical-align: middle;
            padding: 10px 13px;
            border-color: #f1f5f9;
            color: #334155;
        }
        .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: #f8fafc;
        }

        /* ─── Buttons ─── */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            font-size: 13.5px;
            letter-spacing: 0.01em;
            transition: all 0.18s ease;
        }
        .btn-sm {
            border-radius: 6px;
            font-size: 12.5px;
        }
        .btn-xs {
            padding: 3px 9px;
            font-size: 12px;
            border-radius: 5px;
        }

        /* ─── Modals ─── */
        .modal-content {
            border: none;
            border-radius: 14px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.28);
            overflow: hidden;
        }
        .modal-header {
            background: #2d3748;
            border-bottom: none;
            padding: 14px 20px;
        }
        .modal-title {
            color: #f0f4f8 !important;
            font-weight: 600;
            font-size: 15px;
        }
        .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.7;
        }
        .modal-body {
            padding: 20px;
        }
        .modal-footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 12px 20px;
        }

        /* ─── Form controls ─── */
        .form-control,
        .form-select {
            border-radius: 7px;
            font-size: 13.5px;
            /* border: 1px solid #e2e8f0; */
            padding: 8px 12px;
            color: #334155;
        }
        .form-control:focus,
        .form-select:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 3px rgba(67,97,238,0.1);
        }
        label {
            font-size: 13px;
            font-weight: 500;
            color: #475569;
            margin-bottom: 4px;
            display: block;
        }

        /* ─── DataTables ─── */
        .dataTables_wrapper label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 400;
            margin-bottom: 0;
            color: #64748b;
        }
        .dataTables_wrapper .dataTables_filter {
            text-align: right;
        }
        .dataTables_wrapper .dataTables_filter input {
            display: inline-block !important;
            width: 200px !important;
            margin: 0 !important;
        }
        .dataTables_wrapper .dataTables_length select {
            display: inline-block !important;
            width: auto !important;
            padding: 5px 10px !important;
        }
        .dataTables_wrapper .dataTables_info {
            font-size: 13px;
            color: #64748b;
            padding-top: 0.6rem;
        }
        .dataTables_wrapper .dataTables_paginate {
            padding-top: 0.25rem;
        }
        .dataTables_wrapper .page-link {
            font-size: 13px;
            border-radius: 6px !important;
        }
        .dataTables_wrapper > .row {
            align-items: center;
            margin-bottom: 10px;
        }
        .dataTables_wrapper > .row:last-child {
            margin-bottom: 0;
            margin-top: 10px;
        }

        /* ─── Page section title ─── */
        .section-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #94a3b8;
            margin-bottom: 14px;
        }

        /* ─── Gradient hero text ─── */
        .hero-text-gradient {
            --bg-size: 400%;
            --color-one: rgb(37, 161, 244);
            --color-two: rgb(249, 31, 169);
            background: linear-gradient(90deg, var(--color-one), var(--color-two), var(--color-one)) 0 0 / var(--bg-size) 100%;
            color: transparent;
            -webkit-background-clip: text;
            background-clip: text;
            animation: move-bg 24s infinite linear;
        }
        @keyframes move-bg {
            to { background-position: var(--bg-size) 0; }
        }
    </style>
</head>

<body>
<!-- [ Pre-loader ] -->
<div class="loader-bg">
    <div class="loader-track">
        <div class="loader-fill"></div>
    </div>
</div>

<!-- [ Sidebar ] -->
<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="/admin/dashboard" class="b-brand text-primary">
                <img src="{{ asset('assets/images/tib-logo.svg') }}" style="height: 56px; width: auto;"/>
            </a>
        </div>
        <div class="navbar-content">
            <div class="card pc-user-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ asset('assets/images/user/avatar-1.jpg') }}" alt="avatar"
                             class="user-avtar rounded-circle" style="width:38px;height:38px;object-fit:cover;flex-shrink:0;"/>
                        <div style="min-width:0">
                            <h4 class="mb-0 text-truncate" id="display_user">Administrator</h4>
                        </div>
                    </div>
                    <div class="pt-2">
                        <button class="btn btn-danger w-100" id="logout" style="font-size:12.5px;">
                            <i class="ti ti-logout me-1"></i>Log Out
                        </button>
                    </div>
                </div>
            </div>

            <ul class="pc-navbar">
                <!-- ── Admin ── -->
                {{-- <li class="pc-item pc-caption">
                    <label>Admin</label>
                </li> --}}

                <li class="pc-item {{{ Request::is('admin/dashboard') ? 'active' : '' }}}">
                    <a href="/admin/dashboard" class="pc-link">
                        <i class="ti ti-dashboard"></i>
                        <span class="pc-mtext">Dashboard</span>
                    </a>
                </li>

                <!-- ── Penutupan ── -->
                <li class="pc-item pc-hasmenu {{{ Request::is('admin/penutupan/*') ? 'active' : '' }}}">
                    <a href="#!" class="pc-link">
                        <i class="ti ti-file-certificate"></i>
                        <span class="pc-mtext">Penutupan</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item {{{ Request::is('admin/penutupan/data-not-validation') ? 'active' : '' }}}">
                            <a class="pc-link" href="/admin/penutupan/data-not-validation">Dalam Proses</a>
                        </li>
                        <li class="pc-item {{{ Request::is('admin/penutupan/data-validation') ? 'active' : '' }}}">
                            <a class="pc-link" href="/admin/penutupan/data-validation">Terbit Polis</a>
                        </li>
                        <li class="pc-item {{{ Request::is('admin/penutupan/data-expired') ? 'active' : '' }}}">
                            <a class="pc-link" href="/admin/penutupan/data-expired">Polis Kedaluarsa</a>
                        </li>
                        <li class="pc-item {{{ Request::is('admin/penutupan/rekap') ? 'active' : '' }}}">
                            <a class="pc-link" href="/admin/penutupan/rekap">Rekap</a>
                        </li>
                    </ul>
                <li class="pc-item pc-hasmenu {{{ Request::is('admin/penutupan/*') ? 'active' : '' }}}">
                    <a href="#!" class="pc-link">
                        <i class="ti ti-file-alert"></i>
                        <span class="pc-mtext">Klaim</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item {{{ Request::is('admin/penutupan/rekap') ? 'active' : '' }}}">
                            <a class="pc-link" href="/admin/penutupan/rekap">Rekap</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- [ Sidebar ] end -->

<!-- [ Header ] -->
<header class="pc-header">
    <div class="header-wrapper">
        <div class="me-auto pc-mob-drp">
            <ul class="list-unstyled mb-0 d-flex align-items-center gap-1">
                <li class="pc-h-item pc-sidebar-collapse">
                    <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <li class="pc-h-item pc-sidebar-popup">
                    <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                @hasSection('pageTitle')
                <li class="pc-h-item d-none d-md-flex align-items-center ms-2" style="gap:6px;">
                    <span style="color:#bbb;font-size:16px;">›</span>
                    <span style="font-size:13.5px;font-weight:600;color:#333;">@yield('pageTitle')</span>
                </li>
                @endif
            </ul>
        </div>
        <div class="ms-auto d-flex align-items-center gap-2">
            <div class="d-none d-sm-flex align-items-center gap-2"
                 style="font-size:13px;color:#666;border-left:1px solid #eee;padding-left:14px;">
                <i class="ti ti-user-circle" style="font-size:18px;"></i>
                <span id="header_user">Administrator</span>
            </div>
        </div>
    </div>
</header>
<!-- [ Header ] end -->

<!-- [ Content ] -->
<div class="pc-container">
    <div class="pc-content">
        @yield('content')
    </div>
</div>

<input type="hidden" id="token" value="">

<!-- [ Footer ] -->
<footer class="pc-footer">
    <div class="footer-wrapper container-fluid">
        <div class="row">
            <div class="col my-1">
                <span class="m-0">Copyright &copy; {{ date('Y') }} Tugu Insurance Brokers. All rights reserved.</span>
            </div>
        </div>
    </div>
</footer>

<script src="{{ asset('assets/js/plugins/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
<script src="{{ asset('assets/js/config.js') }}"></script>
<script src="{{ asset('assets/js/pcoded.js') }}"></script>
<script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/wow.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/jquery.marquee/1.4.0/jquery.marquee.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="{{ asset('assets/js/plugins/Jarallax.js') }}"></script>
<script>
    window.__cookieDomain = @json(config('setup.domain') ?: null);
</script>
{{-- @vite(['resources/js/api.js', 'resources/js/auth/logout.js']) --}}
<script src="{{ asset('assets/js/plugins/select2.min.js') }}"></script>

@stack('levelPluginsJs')

<script>
    const base_url = '{{ config('setup.base_url') }}';
</script>
</body>
</html>
