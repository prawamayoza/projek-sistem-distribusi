@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>
                        <a href="{{ route('pesanan.index') }}" class="btn btn-icon">
                            <i class="material-icons opacity-10">arrow_back</i>
                        </a> 
                        Detail Pesanan</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Pelanggan</th>
                            <td>{{ $pesanan->pelanggan->name }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Pesanan</th>
                            <td>{{ $pesanan->tanggal }}</td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td>{{ $pesanan->total }}</td>
                        </tr>
                    </table>

                    <h5 class="mt-4">Produk Pesanan</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanan->produk as $product)
                                <tr>
                                    <td>{{ $product->produk }}</td>
                                    <td>{{ $product->jumlah }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
