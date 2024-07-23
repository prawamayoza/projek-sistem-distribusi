@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="w-bold py-3 mb-4">
                        <span class="text-muted fw-light">
                            <a href="{{ route('data-set.index') }}" class="btn btn-icon">
                                <i class="material-icons opacity-10">arrow_back</i>
                            </a>
                        </span>
                        {{ isset($distribusi) ? 'Edit Data Set Distribusi' : 'Tambah Data Set Distribusi' }}
                    </h4>
                    <form id="myForm" class="forms-sample" enctype="multipart/form-data" method="POST" 
                          action="{{ isset($distribusi) ? route('data-set.update', $distribusi->id) : route('data-set.store') }}">
                        {{ csrf_field() }}
                        @if(isset($distribusi))
                            {{ method_field('PUT') }}
                        @endif

                        <div class="input-group input-group-dynamic mb-4">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Nama</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name', @$distribusi->name) }}" required autocomplete="name" 
                                       autofocus placeholder="Nama" aria-label="Nama" aria-describedby="basic-addon1">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="input-group input-group-dynamic mb-4">
                            <label for="tanggal" class="col-md-4 col-form-label text-md-right">Tanggal Distribusi</label>
                            <div class="col-md-6">
                                <input id="tanggal" type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                       name="tanggal" value="{{ old('tanggal', @$distribusi->tanggal) }}" required autocomplete="tanggal" 
                                       placeholder="tanggal" aria-label="tanggal" aria-describedby="basic-addon1">
                                @error('tanggal')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="distances" class="col-form-label d-flex justify-content-between align-items-center">
                                Jarak Antar Pelanggan
                                <button type="button" class="btn btn-success" id="addDistanceRow">Tambah Jarak</button>
                            </label>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="distancesTable">
                                    <thead>
                                        <tr>
                                            <th>Dari Pelanggan</th>
                                            <th>Ke Pelanggan</th>
                                            <th>Jarak (KM)</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($jarakPelanggan))
                                            @foreach($jarakPelanggan as $data)
                                                <tr>
                                                    <td>
                                                        <select name="from_customer[]" class="form-control">
                                                            @foreach($customers as $customer)
                                                                <option value="{{ $customer->id }}" {{ $customer->id == $data->from_customer ? 'selected' : '' }}>
                                                                    {{ $customer->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="to_customer[]" class="form-control">
                                                            @foreach($customers as $customer)
                                                                <option value="{{ $customer->id }}" {{ $customer->id == $data->to_customer ? 'selected' : '' }}>
                                                                    {{ $customer->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="distance[]" class="form-control" value="{{ $data->distance }}" placeholder="Jarak (KM)" required min="0" step="0.01">
                                                    </td>
                                                    <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>
                                                    <select name="from_customer[]" class="form-control">
                                                        @foreach($customers as $customer)
                                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="to_customer[]" class="form-control">
                                                        @foreach($customers as $customer)
                                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" name="distance[]" class="form-control" placeholder="Jarak (KM)" required min="0" step="0.01">
                                                </td>
                                                <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="warehouse_distances" class="col-form-label d-flex justify-content-between align-items-center">
                                Jarak Pelanggan Ke Gudang
                                <button type="button" class="btn btn-success" id="addWarehouseDistanceRow">Tambah Jarak</button>
                            </label>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="warehouseDistancesTable">
                                    <thead>
                                        <tr>
                                            <th>Pelanggan</th>
                                            <th>Jarak (KM)</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($jarakGudang))
                                            @foreach($jarakGudang as $data)
                                                <tr>
                                                    <td>
                                                        <select name="customer_to_warehouse[]" class="form-control">
                                                            @foreach($customers as $customer)
                                                                <option value="{{ $customer->id }}" {{ $customer->id == $data->from_customer ? 'selected' : '' }}>
                                                                    {{ $customer->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="warehouse_distance[]" class="form-control" value="{{ $data->distance }}" placeholder="Jarak (KM)" step="0.01">
                                                    </td>
                                                    <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>
                                                    <select name="customer_to_warehouse[]" class="form-control">
                                                        @foreach($customers as $customer)
                                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" name="warehouse_distance[]" class="form-control" placeholder="Jarak (KM)" step="0.01">
                                                </td>
                                                <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning" id="submitButton">
                            Save <i class="material-icons opacity-10">save</i>
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

                        document.getElementById('addDistanceRow').addEventListener('click', function() {
                            var tableBody = document.querySelector('#distancesTable tbody');
                            var newRow = document.createElement('tr');

                            newRow.innerHTML = `
                                <td>
                                    <select name="from_customer[]" class="form-control">
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="to_customer[]" class="form-control">
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="distance[]" class="form-control" placeholder="Jarak (KM)" step="0.01">
                                </td>
                                <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
                            `;

                            tableBody.appendChild(newRow);
                        });

                        document.getElementById('addWarehouseDistanceRow').addEventListener('click', function() {
                            var tableBody = document.querySelector('#warehouseDistancesTable tbody');
                            var newRow = document.createElement('tr');

                            newRow.innerHTML = `
                                <td>
                                    <select name="customer_to_warehouse[]" class="form-control">
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="warehouse_distance[]" class="form-control" placeholder="Jarak (KM)" step="0.01">
                                </td>
                                <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
                            `;

                            tableBody.appendChild(newRow);
                        });

                        document.addEventListener('click', function(event) {
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
