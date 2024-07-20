@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Daftar Pesanan Pelanggan') }}</span>
                    <div>
                        <a href="{{ route('pesanan.create') }}" class="btn btn-success btn-sm"><i
                                class="material-icons text-sm me-2">add</i>Tambah Data</a>
                        <a href="" class="btn btn-success btn-sm"><i
                                class="material-icons text-sm me-2">file_download</i>Export Data</a>        
                                
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive text-nowrap">
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
                                        <a href="{{ route('pesanan.edit', $item->id) }}"
                                            class="btn btn-primary btn-sm"><i
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
