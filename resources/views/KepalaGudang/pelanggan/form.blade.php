@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="w-bold py-3 mb-4"><span class="text-muted fw-light">
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
                            <label for="name" class="col-md-4 col-form-label text-md-right">Nama</label>
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

                        <div class="form-group mb-4">
                            <label for="orders" class="col-md-4 col-form-label text-md-right">Pesanan</label>
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered" id="ordersTable">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Jumlah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $pesanan = $pesanan ?? [];
                                        @endphp
                                        @foreach ($pesanan as $item)
                                        <tr>
                                            <td><input type="text" name="produk[]" class="form-control" value="{{ $item->produk }}" placeholder="Nama Item"></td>
                                            <td><input type="number" name="jumlah[]" class="form-control" value="{{ $item->jumlah }}" placeholder="Jumlah"></td>
                                            <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-primary" id="addRow">Tambah Item</button>
                            </div>
                        </div>

                        <div class="form-group row mb-0 justify-content-end">
                            <div class="col-md-6 text-right">
                                <button type="submit" class="btn btn-warning" id="submitButton">
                                    {{ $aksi }} <i class="material-icons opacity-10">save</i>
                                    <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                    </form>

                    <script>
                        document.getElementById('myForm').addEventListener('submit', function() {
                            var submitButton = document.getElementById('submitButton');
                            var loadingSpinner = document.getElementById('loadingSpinner');
                            submitButton.disabled = true;
                            loadingSpinner.classList.remove('d-none');
                        });

                        document.getElementById('addRow').addEventListener('click', function() {
                            var tableBody = document.querySelector('#ordersTable tbody');
                            var newRow = document.createElement('tr');

                            newRow.innerHTML = `
                                <td><input type="text" name="produk[]" class="form-control" placeholder="Nama Item"></td>
                                <td><input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah"></td>
                                <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
                            `;

                            tableBody.appendChild(newRow);
                        });

                        document.getElementById('ordersTable').addEventListener('click', function(event) {
                            if (event.target.classList.contains('remove-row')) {
                                event.target.closest('tr').remove();
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
