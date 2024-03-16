@extends('layouts.main')

@section('title')
    Dashboard Admin
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Content Row -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    User</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$user_count}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-table fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Mobil</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$mobil_count}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-table fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Rental</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$rental_count}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-table fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
