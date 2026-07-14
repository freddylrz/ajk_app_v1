@php
    $isClient = Request::is('client*');
    $isTib = Request::is('tib*');
    $isIns = Request::is('ins*');
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <title>@yield('pageTitle', 'TuguBro')</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Asuransi Profesi Tenaga Medis dan Tenaga Kesehatan" />
    <meta name="keywords" content="Asuransi, TuguBro, Broker Asuransi, Tenaga Kesehatan, Tenaga Medis" />
    <meta name="author" content="TuguBro" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

      <link rel="icon" href="{{ asset('assets/images/tib-logo.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/fonts/inter/inter.css') }}" id="main-font-link"/>
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/datepicker-bs5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link"/>
    <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/notifier.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/select.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/select2-bootstrap-5-theme.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}" /> --}}

    @stack('levelPluginsJsHeader')
    @stack('pageStyles')
</head>
@php
    $prefix = request()->segment(1);
@endphp

<body @class([
    'client' => $isClient,
    'ins' => $isIns,
    'tib' => !$isClient && !$isIns,
])>

<style>
    .btn {
        border-radius : 10px !important;
    }
</style>
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
                <a href="{{ $isClient ? route('client.dashboard') : '/admin/dashboard' }}"
                    class="b-brand text-primary">
                    <img src="{{ asset('assets/images/tib-logo.svg') }}" style="height: 56px; width: auto;" />
                </a>
            </div>
            <div class="navbar-content">
                <div class="card pc-user-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('assets/images/user/avatar-1.jpg') }}" alt="avatar"
                                class="user-avtar rounded-circle"
                                style="width:38px;height:38px;object-fit:cover;flex-shrink:0;">

                            <div class="flex-grow-1" style="min-width:0;">
                                <h4 class="mb-0 display_user text-wrap"
                                    style="overflow-wrap: break-word; word-break: break-word;">
                                </h4>
                            </div>
                        </div>
                        <div class="pt-2">
                            <button class="btn btn-danger w-100 btn-sm" id="logout">
                                <i class="ti ti-logout me-1"></i>Keluar
                            </button>
                        </div>
                    </div>
                </div>

                <ul class="pc-navbar">
                    {{-- ══════════ MENU TIB ══════════ --}}
                    <li class="pc-item {{ Request::is("$prefix/dashboard") ? 'active' : '' }}">
                        <a href="/{{ $prefix }}/dashboard" class="pc-link">
                            <i class="ti ti-dashboard"></i>
                            <span class="pc-mtext">Beranda</span>
                        </a>
                    </li>
                    <li class="pc-item pc-hasmenu {{ Request::is("$prefix/penutupan/*") ? 'active' : '' }}">
                        <a href="#!" class="pc-link">
                            <i class="ti ti-file-certificate"></i>
                            <span class="pc-mtext">Penutupan</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            @if ($isClient)
                                <li id="menu-client-input-data" class="pc-item {{ Request::routeIs("$prefix/penutupan/input") ? 'active' : '' }}">
                                    <a class="pc-link" href="/{{ $prefix }}/penutupan/input-data">Input Data
                                    </a>
                                </li>
                            @endif
                            <li class="pc-item {{ Request::is("$prefix/penutupan/list-data") ? 'active' : '' }}">
                                <a class="pc-link" href="/{{ $prefix }}/penutupan/list-data">Dalam Proses</a>
                            </li>
                            <li class="pc-item {{ Request::is("$prefix/penutupan/data-validation") ? 'active' : '' }}">
                                <a class="pc-link" href="/{{ $prefix }}/penutupan/data-validation">Terbit
                                    Polis</a>
                            </li>
                            <li class="pc-item {{ Request::is("$prefix/penutupan/rekap") ? 'active' : '' }}">
                                <a class="pc-link" href="/{{ $prefix }}/penutupan/rekap">Rekap</a>
                            </li>
                        </ul>
                    </li>

                    <li class="pc-item pc-hasmenu {{ Request::is("$prefix/klaim/*") ? 'active' : '' }}">
                        <a href="#!" class="pc-link">
                            <i class="ti ti-file-alert"></i>
                            <span class="pc-mtext">Klaim</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            @if ($isClient)
                                <li class="pc-item {{ Request::routeIs("$prefix/klaim/input-data") ? 'active' : '' }}">
                                    <a class="pc-link" href="/{{ $prefix }}/klaim/input-data">Input Data
                                    </a>
                                </li>
                            @endif
                            <li class="pc-item {{ Request::is("$prefix/klaim/data") ? 'active' : '' }}">
                                <a class="pc-link" href="/{{ $prefix }}/klaim/data">List Data</a>
                            </li>
                            <li class="pc-item {{ Request::is("$prefix/klaim/rekap") ? 'active' : '' }}">
                                <a class="pc-link" href="/{{ $prefix }}/klaim/rekap">Rekap</a>
                            </li>
                        </ul>
                    </li>
                    @if ($isTib)
                        <li class="pc-item pc-hasmenu {{ Request::is('tib/utilities/*') ? 'active' : '' }}">
                            <a href="#!" class="pc-link">
                                <i class="ti ti-settings"></i>
                                <span class="pc-mtext">Pengaturan</span>
                                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                            </a>
                            <ul class="pc-submenu">
                                <li class="pc-item {{ Request::is('tib/utilities/list-user') ? 'active' : '' }}">
                                    <a class="pc-link" href="/tib/utilities/list-user">User</a>
                                </li>
                                <li class="pc-item {{ Request::is('tib/utilities/list-branch') ? 'active' : '' }}">
                                    <a class="pc-link" href="/tib/utilities/list-branch">Cabang</a>
                                </li>
                            </ul>
                        </li>
                    @endif
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
                    style="font-size:13px;font-weight:400;color:#333;border-left:1px solid #eee;padding-left:14px;">
                    <i class="ti ti-user-circle" style="font-size:14px;"></i>
                    <span class="display_user"></span>
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
                    <span class="m-0">Copyright &copy; {{ date('Y') }} Tugu Insurance Brokers. All rights
                        reserved.</span>
                </div>
            </div>
        </div>
    </footer>

    {{-- JS inti template --}}
    <script src="{{ asset('assets/js/plugins/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <script src="{{ asset('assets/js/pcoded.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/notifier.js') }}"></script>

    {{-- JS plugin umum --}}
    <script src="{{ asset('assets/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/datepicker-full.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>

    @vite(['resources/js/auth/logout.js', 'resources/js/client/role-guard.js'])

    <script>
        window.__cookieDomain = @json(config('setup.domain') ?: null);
        const base_url = '{{ config('setup.base_url') }}';
    </script>

    @stack('levelPluginsJs')
</body>

</html>
