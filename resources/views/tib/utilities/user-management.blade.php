@extends('layouts.app')

@section('content')
    <style>
        .table thead tr th {
            text-align: center;
        }
    </style>
    <div class="pct-body">
        <div class="card">
            <div class="card-header">
                <h3> Daftar Akun</h3>
            </div>

            <div class="card-body">
                <button class="btn btn-primary mb-3 pull-right btn-sm" id="btnAddUser"><i class="fas fa-plus"></i> Tambah
                    Akun</button>
                <div class="table-responsive dt-responsive">
                    <table id="table" class="table table-striped table-bordered nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Cabang</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-edit">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLiveLabel">Ubah Password Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="text-transform: uppercase;">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" required name="newpass"
                                        placeholder="Password" id="npassword">
                                    <span class="input-group-text bg-transparent"><i class="fas fa-eye"
                                            id="newtogglePassword"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="text-transform: uppercase;">Konfirmasi Password</label>
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" required placeholder="Password"
                                        name="newPassword" id="newPassword">
                                </div>
                                <div id="divNewCheckPasswordMatch"></div>
                            </div>
                            <input type="hidden" id="userId">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success pull-right" id="saveEdit">Simpan</button>
                    <button type="button" class="btn btn-danger pull-right" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-add">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLiveLabel">Tambah Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action=" " id="formUser">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        Display Name
                                    </label>
                                    <input type="text" id="display" name="display" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>
                                        Username
                                    </label>
                                    <input type="text" id="name" name="name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>
                                        Role
                                    </label>
                                    <select name="role_id" id="role_id" class="form-control" required>

                                    </select>
                                </div>
                                <div class="form-group" style="display: none" id="divCabang">
                                    <label>
                                        Cabang
                                    </label>
                                    <select name="branch_id[]" id="branch_id" class="form-control select2" multiple
                                        required>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="">Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" required name="newpass"
                                            placeholder="Password" id="password" required>
                                        <span class="input-group-text bg-transparent"><i class="fas fa-eye"
                                                id="togglePassword"></i></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label style="">Konfirmasi Password</label>
                                    <div class="input-group mb-3">
                                        <input type="password" class="form-control" required placeholder="Password"
                                            name="password" id="confirm_password" required>
                                    </div>
                                    <div id="divCheckPasswordMatch"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success pull-right">Simpan</button>
                        <button type="button" class="btn btn-danger pull-right" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>  
        <!-- /.modal-dialog -->
    </div>
    @push('levelPluginsJsHeader')
        <link rel="stylesheet" href="{{ asset('assets/css/plugins/dataTables.bootstrap5.min.css') }}">
    @endpush

    @push('levelPluginsJs')
        <script src="{{ asset('assets/js/plugins/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>
        @vite(['resources/js/utilities/management_user.js'])
    @endpush
@endsection
