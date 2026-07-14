@extends('layouts.app')

@section('pageTitle', 'Beranda')
@section('pageIcon', 'ti ti-home')

@section('content')

    <style>
        .card-body span {
            font-size: 18px !important;
        }
    </style>
    <div class="pct-body">
        <div class="container-fluid py-4">

            <!-- ================= SUMMARY ================= -->
            <div class="row g-3 mb-4">

                <div class="col-lg-4 col-md-4">
                    <div class="card social-widget-card border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <h4 class="mb-2 text-primary">Total Plafond</h4>

                            <div class="text-end">
                                <span class="fw-bold text-primary fs-4" id="total_plafond">
                                    0
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4">
                    <div class="card social-widget-card border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <h4 class="mb-2 text-success">Total Premi</h4>

                            <div class="text-end">
                                <span class="fw-bold fs-4 text-success" id="total_premium">
                                    0
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4">
                    <div class="card social-widget-card border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <h4 class="mb-2 text-warning">Total Debitur</h4>

                            <div class="text-end">
                                <span class="fw-bold text-warning fs-4" id="total_debitur">
                                    0
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h4 id="bulanBerjalan"></h4>
            <div class="row g-3 mb-4">
                <div class="col-lg-4 col-md-4">
                    <div class="card social-widget-card border-0 shadow-sm h-100 bg-primary-subtle">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <h4 class="text-muted mb-2"> Plafond</h4>

                            <div class="text-end">
                                <span class="fw-bold text-secondary fs-4" id="total_plafond_month">
                                    0
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4">
                    <div class="card social-widget-card border-0 shadow-sm h-100 bg-success-subtle">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <h4 class="text-muted mb-2"> Premi</h4>

                            <div class="text-end">
                                <span class="fw-bold text-secondary fs-4" id="total_premium_month">
                                    0
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4">
                    <div class="card social-widget-card border-0 shadow-sm h-100 bg-warning-subtle">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <h4 class="text-muted mb-2"> Debitur</h4>

                            <div class="text-end">
                                <span class="fw-bold text-secondary fs-4" id="total_debitur_month">
                                    0
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ================= CHART ================= -->

            <div class="row g-3 mb-4">

                <div class="col-lg-8">
                    <div class="card social-widget-card border-0 shadow-sm h-100">
                        <div class="card-header bg-white">
                            <h5>
                                {{-- <i class="bi bi-bar-chart-line-fill text-primary"></i> --}}
                                Statistik Tahunan
                            </h5>
                        </div>
                        <div class="card-body">

                            <div id="yearlyChart" style="height:180px"></div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-4">

                    <div class="card social-widget-card border-0 shadow-sm h-100">

                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                {{-- <i class="bi bi-pie-chart-fill text-success"></i> --}}
                                Kategori Debitur
                            </h5>
                        </div>

                        <div class="card-body">

                            <div id="categoryChart" style="height:180px"></div>

                            <hr>

                            <div id="categoryList">

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- ================= CLAIM ================= -->

            <div class="row g-3 mb-4">

                <div class="col-lg-3">

                    <div class="card social-widget-card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">

                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                <i class="bi bi-file-earmark-medical fs-3 text-primary"></i>
                            </div>

                            <div>
                                <h4 class="text-muted">Total Klaim</h4>
                                <h3 class="fw-bold mb-0" id="total_claim">0</h3>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="col-lg-3">

                    <div class="card social-widget-card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">

                            <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                                <i class="bi bi-hourglass-split fs-3 text-warning"></i>
                            </div>

                            <div>
                                <h4 class="text-muted">Diproses</h4>
                                <h3 class="fw-bold mb-0" id="claim_process">0</h3>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="col-lg-3">

                    <div class="card social-widget-card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">

                            <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">
                                <i class="bi bi-x-circle fs-3 text-danger"></i>
                            </div>

                            <div>
                                <h4 class="text-muted">Ditolak</h4>
                                <h3 class="fw-bold mb-0" id="claim_reject">0</h3>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="col-lg-3">

                    <div class="card social-widget-card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">

                            <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                                <i class="bi bi-check-circle fs-3 text-success"></i>
                            </div>

                            <div>
                                <h4 class="text-muted">Dibayar</h4>
                                <h3 class="fw-bold mb-0">
                                    <span id="claim_approve">0</span> /
                                    <span id="claim_paid">0</span>
                                </h3>
                            </div>

                        </div>
                    </div>

                </div>

            </div>

            <!-- ================= TABLE ================= -->

            <div class="card social-widget-card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        {{-- <i class="bi bi-table"></i> --}}
                        Klaim per Kategori
                    </h5>

                </div>

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>Kategori</th>
                                <th class="text-center">Debitur</th>
                                <th class="text-center">Klaim</th>

                            </tr>

                        </thead>

                        <tbody id="claimCategoryTable">

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    </div>

@endsection
@push('levelPluginsJsHeader')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush
@push('levelPluginsJs')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.49.2"></script>
    @vite(['resources/js/dashboard.js'])
@endpush
