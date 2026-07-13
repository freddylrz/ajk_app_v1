@extends('layouts.app')

@section('content')

<style>
    .table thead tr th{
        text-align: center;
    }

</style>
    <div class="pct-body">
        <div class="card">
            <div class="card-header">
                <h3> Daftar Cabang</h3>
            </div>

            <div class="card-body">
                <button class="btn btn-primary btn-sm mb-3 pull-right" id="btnAddUser"><i
                        class="fas fa-plus"></i> Tambah Cabang</button>
                <div class="table-responsive dt-responsive">
                    <table id="table" class="table table-striped table-bordered nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Cabang</th>
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
    <div class="modal fade" id="modal-add">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLiveLabel">Tambah Cabang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action=" " id="formBranch">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>
                                        Nama Cabang
                                    </label>
                                    <input type="text" id="branch_name" name="branch_name" class="form-control" required>
                                    <input type="hidden" id="branch_id" name="branch_id" class="form-control" value="0">
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
        @vite(['resources/js/utilities/management_branch.js'])
    @endpush
@endsection