@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Daftar Pesanan Pelanggan </h4>
                    @role('kepala gudang')
                    <div>
                        <a href="{{ route('pesanan.create') }}" class="btn btn-success btn-sm"><i
                                class="material-icons text-sm me-2">add</i>Tambah Data</a>
                        <a href="{{ route('pesanan.export') }}" class="btn btn-success btn-sm"><i
                                class="material-icons text-sm me-2">import_export</i>Export Data</a>        
                    </div>
                    @endrole
                </div>

                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        @role('kepala gudang')
                        <table id="mytable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">Nomor</th>
                                    <th scope="col" class="text-center">Nama Pelanggan</th>
                                    <th scope="col" class="text-center">Tanggal Pesanan</th>
                                    <th scope="col" class="text-center">Total Pesanan</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                 @forelse ($pesanan as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $item->pelanggan->name }}</td>
                                    <td class="text-center">{{ $item->tanggal }}</td>
                                    <td class="text-center">{{ $item->total }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('pesanan.show', $item->id)}}" class="btn btn-info btn-sm"><i
                                            class="material-icons text-sm me-2">remove_red_eye</i>Detail</a>
                                        <a href="{{ route('pesanan.edit', $item->id) }}"
                                            class="btn btn-warning btn-sm"><i
                                                class="material-icons text-sm me-2">edit</i> Edit</a>
                                        <button value="{{ route('pesanan.destroy', $item->id) }}"
                                                            class="btn btn-sm btn-danger delete"
                                                            data-toggle="tooltip" data-placement="top" title="Hapus"> <i
                                                class="material-icons text-sm me-2">delete</i> Hapus
                                        </button>   
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada pengguna ditemukan</td>
                                </tr>
                                @endforelse 
                            </tbody>
                        </table>
                        @endrole
                        @role(['driver','manager'])
                        <table id="mytable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama Pelanggan</th>
                                    <th scope="col">Tanggal Pesanan</th>
                                    <th scope="col">Total Pesanan</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                 @forelse ($pesanan as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->pelanggan->name }}</td>
                                    <td>{{ $item->tanggal }}</td>
                                    <td>{{ $item->total }}</td>
                                    <td>
                                        <a href="{{ route('pesanan.show', $item->id)}}" class="btn btn-info btn-sm"><i
                                            class="material-icons text-sm me-2">remove_red_eye</i>Detail</a>
                                        {{-- <a href="{{ route('pesanan.edit', $item->id) }}"
                                            class="btn btn-primary btn-sm"><i
                                                class="material-icons text-sm me-2">edit</i> Edit</a>
                                        <button value="{{ route('pesanan.destroy', $item->id) }}"
                                                            class="btn btn-sm btn-danger delete"
                                                            data-toggle="tooltip" data-placement="top" title="Hapus"> <i
                                                class="material-icons text-sm me-2">delete</i> Hapus
                                        </button>    --}}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada pengguna ditemukan</td>
                                </tr>
                                @endforelse 
                            </tbody>
                        </table>
                        @endrole
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
