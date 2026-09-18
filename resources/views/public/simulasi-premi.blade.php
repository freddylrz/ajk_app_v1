<!DOCTYPE html>
<html lang="id">
   <!-- [Head] start -->
   <head>
      <title>Simulasi Hitung Premi</title>
      <!-- [Meta] -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="description" content="Simulasi hitung premi asuransi kredit, Tugu Insurance Brokers. Hitung estimasi premi tanpa perlu login.">
      <meta name="keywords" content="simulasi premi, hitung premi, broker asuransi, Tugu Insurance Brokers">
      <meta name="author" content="Tugu Insurance Brokers">
      <meta name="csrf-token" content="{{ csrf_token() }}">

      <!-- [Favicon] icon -->
      <link rel="icon" href="{{ asset('assets/images/tib-logo.svg') }}" type="image/x-icon">
      <!-- [Font] Family -->
      <link rel="stylesheet" href="{{ asset('assets/fonts/inter/inter.css') }}" id="main-font-link" />
      <!-- [Datepicker] harus dimuat SEBELUM style.css agar aturan .form-control{display:block}
           milik style.css (yang datang belakangan) yang menang saat terjadi tie specificity
           dengan .datepicker{display:none} milik plugin ini — jika urutan dibalik, input
           tanggal (yang juga memakai class "datepicker" sebagai hook JS) akan collapse/hilang. -->
      <link rel="stylesheet" href="{{ asset('assets/css/plugins/datepicker-bs5.min.css') }}">
      <!-- [Tabler Icons] https://tablericons.com -->
      <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}" />
      <!-- [Feather Icons] https://feathericons.com -->
      <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}" />
      <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
      <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}" />
      <!-- [Material Icons] https://fonts.google.com/icons -->
      <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}" />
      <!-- [Template CSS Files] -->
      <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link" />
      <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}" />
   </head>
   <!-- [Head] end -->
   <!-- [Body] Start -->
   <body>
      <!-- [ Pre-loader ] start -->
      <div class="loader-bg">
         <div class="loader-track">
            <div class="loader-fill"></div>
         </div>
      </div>
      <!-- [ Pre-loader ] End -->
      <div class="auth-main">
         <div class="auth-wrapper v1">
            <div class="auth-form">
               <div class="container">
                  <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12">
                     <div class="card bg-light my-5" style="box-shadow: 0 2rem 2rem rgba(0, 0, 0, 0.175) !important; border: 2px solid #dddddd;">
                        <div class="card-body">
                           <div class="my-4 my-md-2 text-center">
                              <img src="{{ asset('assets/images/tib-logo.svg') }}" alt="Logo PT Tugu Insurance Brokers" style="width: 165px;">
                              <h4 class="mt-3 mb-0">Simulasi Hitung Premi</h4>
                              <p class="text-muted mb-0">Hitung estimasi premi tanpa perlu login</p>
                           </div>

                           <div class="row">
                              <div class="col-md-3">
                                 <div class="form-group">
                                    <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                       <input type="text" class="form-control datepicker" id="tanggal_lahir"
                                              placeholder="dd-mm-yyyy" required>
                                       <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group">
                                    <label class="form-label">Periode Awal <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                       <input type="text" class="form-control datepicker" id="periode_awal"
                                              placeholder="dd-mm-yyyy" required>
                                       <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group">
                                    <label class="form-label">Tenor <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                       <input type="number" class="form-control" id="tenor" placeholder="Tenor" min="1" required>
                                       <span class="input-group-text">Bulan</span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group">
                                    <label class="form-label">Plafond Kredit <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                       <span class="input-group-text">Rp</span>
                                       <input type="text" class="form-control text-end" id="plafond_kredit"
                                              placeholder="Plafond Kredit" required>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="text-end mt-3 mb-3">
                              <button type="button" class="btn btn-primary" id="btn-hitung">
                                 <i class="ti ti-calculator"></i> Hitung
                              </button>
                           </div>

                           <hr>

                           <h5 class="mb-3">Hasil :</h5>
                           <div class="row">
                              <div class="col-md-3">
                                 <small class="text-muted d-block">Umur</small>
                                 <strong id="output_umur">-</strong>
                              </div>
                              <div class="col-md-3">
                                 <small class="text-muted d-block">Periode</small>
                                 <strong id="output_periode">-</strong>
                              </div>
                              <div class="col-md-3">
                                 <small class="text-muted d-block">Rate</small>
                                 <strong id="output_rate">-</strong>
                              </div>
                              <div class="col-md-3">
                                 <small class="text-muted d-block">Premi</small>
                                 <strong id="output_premi">-</strong>
                              </div>
                           </div>

                           <div class="d-grid mt-4">
                              <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                                 <i class="ti ti-arrow-left"></i> Kembali ke Halaman Login
                              </a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- [ Main Content ] end -->
      <!-- Jquery -->
      <script src="{{ asset('assets/js/plugins/jquery-3.7.1.min.js') }}"></script>
      <!-- Required Js -->
      <script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>

      <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
      <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
      <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
      <script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
      <script src="{{ asset('assets/js/config.js') }}"></script>
      <script src="{{ asset('assets/js/pcoded.js') }}"></script>
      <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
      <script src="{{ asset('assets/js/plugins/datepicker-full.min.js') }}"></script>
      @vite(['resources/js/public/simulasi-premi.js'])
      <script>
        const authBackendUrl = '{{ config('setup.auth_backend_url') }}';
        const domain = '{{ config('setup.domain') }}';
      </script>
   </body>
   <!-- [Body] end -->
</html>
