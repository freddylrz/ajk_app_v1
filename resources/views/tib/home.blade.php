@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-4 col-sm-12 col-xs-12">
       <div class="card social-widget-card bg-primary">
              <div class="card-body">
                <h3 class="text-white m-0">IDR 10,000,000,000</h3>
                <span class="m-t-10">Total Nilai Pertanggungan</span>
              </div>
                {{-- <i class="ti ti-building-bank"></i> --}}
          </div>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="card social-widget-card bg-success">
              <div class="card-body">
                <h3 class="text-white m-0">IDR 100,000,000</h3>
                <span class="m-t-10">Total Premi</span>
                {{-- <i class="ti ti-bucket"></i> --}}
              </div>
            </div>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="card social-widget-card bg-secondary">
              <div class="card-body">
                <h3 class="text-white m-0">500</h3>
                <span class="m-t-10">Total Debitur</span>
                {{-- <i class="ti ti-users"></i> --}}
              </div>
            </div>
    </div>
</div>

@endsection
