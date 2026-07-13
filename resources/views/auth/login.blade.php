<!DOCTYPE html>
<html lang="en">
   <!-- [Head] start -->
   <head>
      <title>Login</title>
      <!-- [Meta] -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="description" content="Login aplikasi broker asuransi, Tugu Insurance Brokers. Aplikasi untuk mengelola dan mengakses layanan asuransi broker secara mudah dan aman.">
      <meta name="keywords" content="login, broker asuransi, Tugu Insurance Brokers, aplikasi asuransi, layanan asuransi, manajemen asuransi">
      <meta name="author" content="Tugu Insurance Brokers">
       <meta name="csrf-token" content="{{ csrf_token() }}">

      <!-- [Favicon] icon -->
      <link rel="icon" href="{{ asset('assets/images/tib-logo.svg') }}" type="image/x-icon">
      <!-- [Font] Family -->
      <link rel="stylesheet" href="{{ asset('assets/fonts/inter/inter.css') }}" id="main-font-link" />
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
                  <div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-12">
                     <div class="card bg-light my-5" style="box-shadow: 0 2rem 2rem rgba(0, 0, 0, 0.175) !important; border: 2px solid #dddddd;">
                        <div class="card-body">
                            <form method="POST" id="loginForm" aria-label="{{ __('Login') }}">
                                @csrf
                                <div class="my-4 my-md-2 text-center">
                                <img src="{{ asset('assets/images/tib-logo.svg') }}" alt="Logo PT Tugu Insurance Brokers di login"  style="width: 165px;">
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Username</label>
                                <div class="input-group mb-3">
                                   <input type="text" class="form-control" id="username" name="username" placeholder="Username" required autocomplete="username">
                                   <div class="input-group-text">
                                      <span class="fas fa-user"></span>
                                   </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Password</label>
                                <div class="input-group mb-3">
                                   <input type="password" class="form-control" id="password" name="password" placeholder="Password" required autocomplete="current-password">
                                   <div class="input-group-text toggle-password">
                                      <i class="fas fa-eye"></i>
                                   </div>
                                </div>
                            </div>
                           <div class="d-grid mt-4">
                              <button type="submit" class="btn btn-primary" id="btn-submit">Masuk</button>
                           </div>
                            </form>
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
      @vite(['resources/js/auth/login.js'])
      <script>
        const authBackendUrl = '{{ config('setup.auth_backend_url') }}';
        const domain = '{{ config('setup.domain') }}';
    </script>
   </body>
   <!-- [Body] end -->
</html>
