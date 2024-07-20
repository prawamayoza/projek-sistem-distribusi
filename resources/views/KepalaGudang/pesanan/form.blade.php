@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ $title ?? 'Tambah Pesanan' }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ isset($pesanan) ? route('pesanan.update', $pesanan->id) : route('pesanan.store') }}" id="myForm">
                        @csrf
                        @if(isset($pesanan))
                            @method('PUT')
                        @endif
                        <input type="hidden" name="pelanggan_id" value="{{ $pelanggan_id }}">

                        <div class="form-group mb-4">
                            <label for="orders" class="col-md-4 col-form-label text-md-right">Pesanan</label>
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered" id="ordersTable">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $items = $pesanan->items ?? []; // Adjust as needed
                                        @endphp
                                        @foreach ($items as $item)
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

                        <button type="submit" class="btn btn-warning" id="submitButton">
                            {{ isset($pesanan) ? 'Update' : 'Save' }} <i class="material-icons opacity-10">save</i>
                            <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
@endsection
