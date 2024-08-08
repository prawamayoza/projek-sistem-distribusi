@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="w-bold py-3 mb-4">
                        <a href="{{ route('pelanggan.index') }}" class="btn btn-icon">
                            <i class="material-icons opacity-10">arrow_back</i>
                        </a>
                        @if (@$pelanggan->exists)
                            Edit
                            @php
                                $aksi = 'Save';
                            @endphp
                        @else
                            Tambah
                            @php
                                $aksi = 'Save';
                            @endphp
                        @endif
                        Pelanggan
                    </h4>
                    @if (@$pelanggan->exists)
                        <form id="myForm" class="forms-sample" enctype="multipart/form-data" method="POST" action="{{ route('pelanggan.update', $pelanggan) }}">
                            @method('put')
                    @else
                        <form id="myForm" class="forms-sample" enctype="multipart/form-data" method="POST" action="{{ route('pelanggan.store') }}">
                    @endif
                    {{ csrf_field() }}

                    <div class="input-group input-group-dynamic mb-4">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Kode Rute</label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', @$pelanggan->name) }}" required autocomplete="name" autofocus placeholder="Nama Pelanggan" aria-label="Nama" aria-describedby="basic-addon1">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="input-group input-group-dynamic mb-4">
                        <label for="pelanggan" class="col-md-4 col-form-label text-md-right">Nama Pelanggan</label>
                        <div class="col-md-6">
                            <input id="pelanggan" type="text" class="form-control @error('pelanggan') is-invalid @enderror" name="pelanggan" value="{{ old('pelanggan', @$pelanggan->pelanggan) }}" required autocomplete="pelanggan" autofocus placeholder="Nama Pelanggan" aria-label="Nama" aria-describedby="basic-addon1">
                            @error('pelanggan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="input-group input-group-dynamic mb-4">
                        <label for="alamat" class="col-md-4 col-form-label text-md-right">Alamat</label>
                        <div class="col-md-6">
                            <input id="alamat" type="alamat" class="form-control @error('alamat') is-invalid @enderror" name="alamat" value="{{ old('alamat', @$pelanggan->alamat) }}" required autocomplete="Alamat" placeholder="Alamat" aria-label="Alamat" aria-describedby="basic-addon1">
                            @error('alamat')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="input-group input-group-dynamic mb-4">
                        <label for="no_telepon" class="col-md-4 col-form-label text-md-right">No Telpon</label>
                        <div class="col-md-6">
                            <input id="no_telpon" type="number" class="form-control @error('no_telpon') is-invalid @enderror" name="no_telpon" value="{{ old('no_telpon', @$pelanggan->no_telpon) }}" required autocomplete="no_telpon" placeholder="Mausukkan data No Telpon Pelanggan" aria-label="no_telpon" aria-describedby="basic-addon1" min="1">
                            @error('no_telpon')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                            <button type="submit" class="btn btn-warning" id="submitButton">
                                {{ $aksi }} <i class="material-icons opacity-10">save</i>
                                <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                    </form>

                    <script>
                        document.getElementById('myForm').addEventListener('submit', function() {
                            var submitButton = document.getElementById('submitButton');
                            var loadingSpinner = document.getElementById('loadingSpinner');
                            submitButton.disabled = true;
                            loadingSpinner.classList.remove('d-none');
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
