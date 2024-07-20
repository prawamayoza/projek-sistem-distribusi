@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="w-bold py-3 mb-4">
                        <span class="text-muted fw-light">
                            <a href="{{ route('pesanan.index') }}" class="btn btn-icon">
                                <i class="material-icons opacity-10">arrow_back</i>
                            </a>
                            {{ isset($pesanan) ? 'Edit Pesanan' : 'Tambah Pesanan' }}
                        </span>
                    </h4>
                    <form id="myForm" class="forms-sample" enctype="multipart/form-data" method="POST" action="{{ isset($pesanan) ? route('pesanan.update', $pesanan) : route('pesanan.store') }}">
                        @csrf
                        @if(isset($pesanan))
                            @method('PUT')
                        @endif

                        <div class="input-group input-group-dynamic mb-4">
                            <label for="pelanggan" class="col-md-4 col-form-label text-md-right">Pilih Pelanggan</label>
                            <div class="col-md-6">
                                <select id="pelanggan" class="form-control @error('pelanggan') is-invalid @enderror" name="pelanggan_id" required>
                                    <option value="">-- Pilih Pelanggan --</option>
                                    @foreach($pelanggan as $customer)
                                        <option value="{{ $customer->id }}" {{ (isset($pesanan) && $pesanan->pelanggan_id == $customer->id) ? 'selected' : '' }}>{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                                @error('pelanggan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="input-group input-group-dynamic mb-4">
                            <label for="tanggal" class="col-md-4 col-form-label text-md-right">Tanggal Pesanan</label>
                            <div class="col-md-6">
                                <input id="tanggal" type="date" class="form-control @error('tanggal') is-invalid @enderror" name="tanggal" value="{{ isset($pesanan) ? $pesanan->tanggal : old('tanggal') }}" required autocomplete="tanggal" placeholder="tanggal" aria-label="tanggal" aria-describedby="basic-addon1">
                                @error('tanggal')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="produk" class="col-form-label d-flex justify-content-between align-items-center">Produk
                                <button type="button" class="btn btn-success" onclick="addRow()">Tambah Produk</button>
                            </label>
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered" id="produkTable">
                                    <thead>
                                        <tr>
                                            <th>Nama Produk</th>
                                            <th>Jumlah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($produk) && $produk->count() > 0)
                                            @foreach($produk as $product)
                                                <tr>
                                                    <td><input type="text" name="produk[]" class="form-control" placeholder="Nama Produk" required value="{{ $product->produk }}"></td>
                                                    <td><input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" required min="1" value="{{ $product->jumlah }}" oninput="updateTotal()"></td>
                                                    <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Hapus</button></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td><input type="text" name="produk[]" class="form-control" placeholder="Nama Produk" required></td>
                                                <td><input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" required min="1" oninput="updateTotal()"></td>
                                                <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Hapus</button></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="totalJumlah" class="col-md-4 col-form-label text-md-right">Total Jumlah Produk: </label>
                            <div class="col-md-6 d-flex align-items-center">
                                <input id="totalJumlah" type="text" class="form-control" name="total" value="{{ isset($pesanan) ? $pesanan->total : 0 }}" readonly>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning" id="submitButton">
                            Save <i class="material-icons opacity-10">save</i>
                            <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </form>

                    <script>
                        function addRow() {
                            const table = document.getElementById('produkTable').getElementsByTagName('tbody')[0];
                            const newRow = table.insertRow();
                            const produkCell = newRow.insertCell(0);
                            const jumlahCell = newRow.insertCell(1);
                            const aksiCell = newRow.insertCell(2);

                            produkCell.innerHTML = '<input type="text" name="produk[]" class="form-control" placeholder="Nama Produk" required>';
                            jumlahCell.innerHTML = '<input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" required min="1" oninput="updateTotal()">';
                            aksiCell.innerHTML = '<button type="button" class="btn btn-danger" onclick="removeRow(this)">Hapus</button>';

                            updateTotal();
                        }

                        function removeRow(button) {
                            const row = button.parentNode.parentNode;
                            row.parentNode.removeChild(row);

                            updateTotal();
                        }

                        function updateTotal() {
                            const jumlahInputs = document.querySelectorAll('input[name="jumlah[]"]');
                            let total = 0;
                            jumlahInputs.forEach(input => {
                                total += parseInt(input.value) || 0;
                            });
                            document.getElementById('totalJumlah').value = total;
                        }

                        document.getElementById('myForm').addEventListener('submit', function() {
                            var submitButton = document.getElementById('submitButton');
                            var loadingSpinner = document.getElementById('loadingSpinner');
                            submitButton.disabled = true;
                            loadingSpinner.classList.remove('d-none');
                        });

                        window.onload = updateTotal; // Initialize total on page load
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
