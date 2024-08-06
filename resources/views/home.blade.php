@extends('layouts.app')

@section('content')
<div class="container position-relative">
    <div class="card">
        <div class="card-header">{{ __('Dashboard') }}</div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <p>{{ __('You are logged in!') }}</p>
            <br>
            <div class="container-fluid py-4 mb-3">
                <div class="row">
                    @role('kepala gudang')
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2 position-relative">
                                    <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute top-0 start-0 translate-middle">
                                        <i class="material-icons opacity-10">person</i>
                                    </div>
                                    <div class="text-end pt-3">
                                        <p class="text-sm mb-0 text-capitalize">Total User</p>
                                        <h4 class="mb-0">{{ $user }}</h4>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2 position-relative">
                                    <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute top-0 start-0 translate-middle">
                                        <i class="material-icons opacity-10">person</i>
                                    </div>
                                    <div class="text-end pt-3">
                                        <p class="text-sm mb-0 text-capitalize">Total Pelanggan</p>
                                        <h4 class="mb-0">{{ $pelanggan }}</h4>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2 position-relative">
                                    <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute top-0 start-0 translate-middle">
                                        <i class="material-icons opacity-10">shopping_basket</i>
                                    </div>
                                    <div class="text-end pt-3">
                                        <p class="text-sm mb-0 text-capitalize">Total Pesanan</p>
                                        <h4 class="mb-0">{{ $pesanan }} </h4>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2 position-relative">
                                    <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute top-0 start-0 translate-middle">
                                        <i class="material-icons opacity-10">directions_car</i>
                                    </div>
                                    <div class="text-end pt-3">
                                        <p class="text-sm mb-0 text-capitalize">Kendaraan Available</p>
                                        <h4 class="mb-0">{{ $kendaraan }}</h4>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                            </div>
                        </div>
                    @endrole
                    @role('driver')
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2 position-relative">
                                    <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute top-0 start-0 translate-middle">
                                        <i class="material-icons opacity-10">shopping_basket</i>
                                    </div>
                                    <div class="text-end pt-3">
                                        <p class="text-sm mb-0 text-capitalize">Total Pesanan Hari Ini</p>
                                        <h4 class="mb-0">{{ $pesanan }} </h4>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2 position-relative">
                                    <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute top-0 start-0 translate-middle">
                                        <i class="material-icons opacity-10">directions_car</i>
                                    </div>
                                    <div class="text-end pt-3">
                                        <p class="text-sm mb-0 text-capitalize">Kendaraan Available</p>
                                        <h4 class="mb-0">{{ $kendaraan }}</h4>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                            </div>
                        </div>
                    @endrole
                    @role('manager')
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2 position-relative">
                                    <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute top-0 start-0 translate-middle">
                                        <i class="material-icons opacity-10">shopping_basket</i>
                                    </div>
                                    <div class="text-end pt-3">
                                        <p class="text-sm mb-0 text-capitalize">Total Pesanan</p>
                                        <h4 class="mb-0">{{ $pesanan }} </h4>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2 position-relative">
                                    <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute top-0 start-0 translate-middle">
                                        <i class="material-icons opacity-10">person</i>
                                    </div>
                                    <div class="text-end pt-3">
                                        <p class="text-sm mb-0 text-capitalize">Total Pelanggan</p>
                                        <h4 class="mb-0">{{ $pelanggan }}</h4>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mb-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2 position-relative">
                                    <div class="icon icon-lg icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute top-0 start-0 translate-middle">
                                        <i class="material-icons opacity-10">check_circle</i>
                                    </div>
                                    <div class="text-end pt-3">
                                        <p class="text-sm mb-0 text-capitalize">Data Perhitungan Yang Belum Approve</p>
                                        <h4 class="mb-0">{{ $distribusi }}</h4>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                            </div>
                        </div>
                    @endrole
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
