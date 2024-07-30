@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="w-bold py-3 mb-4"> 
                            <a href="{{ route('kendaraan.index') }}"
                                class="btn btn-icon">
                                <i class="material-icons opacity-10">arrow_back</i>
                            </a>
                            @if (@$kendaraan->exists)
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
                            Kendaraan
                    </h4>
                    @if (@$kendaraan->exists)
                        <form id="myForm" class="forms-sample" enctype="multipart/form-data" method="POST"
                            action="{{ route('kendaraan.update', $kendaraan) }}">
                            @method('put')
                        @else
                            <form id="myForm" class="forms-sample" enctype="multipart/form-data" method="POST"
                                action="{{ route('kendaraan.store') }}">
                    @endif
                    {{ csrf_field() }}

                        <div class="input-group input-group-dynamic mb-4">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Nama</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', @$kendaraan->name) }}" required autocomplete="name" autofocus placeholder="Nama kendaraan" aria-label="Nama" aria-describedby="basic-addon1">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="input-group input-group-dynamic mb-4">
                            <label for="kapasitas" class="col-md-4 col-form-label text-md-right">Kapasitas Mobil</label>

                            <div class="col-md-6">
                                <input id="kapasitas" type="number" class="form-control @error('kapasitas') is-invalid @enderror" name="kapasitas" value="{{ old('kapasitas', @$kendaraan->kapasitas) }}" required autocomplete="kapasitas" placeholder="kapasitas" aria-label="kapasitas" aria-describedby="basic-addon1" min="1">
                                @error('kapasitas')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="input-group input-group-dynamic mb-4">
                            <label for="kapasitas" class="col-md-4 col-form-label text-md-right">Jarak Tempuh (KM)/Liter</label>

                            <div class="col-md-6">
                                <input id="jarakPerliter" type="number" class="form-control @error('jarakPerliter') is-invalid @enderror" name="jarakPerliter" value="{{ old('jarakPerliter', @$kendaraan->jarakPerliter) }}" required autocomplete="jarakPerliter" placeholder="jarakPerliter" aria-label="jarakPerliter" aria-describedby="basic-addon1" min="1">
                                @error('jarakPerliter')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                                <button type="submit" class="btn btn-warning" id="submitButton">
                                {{ $aksi}} <i class="material-icons opacity-10">save</i>
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
