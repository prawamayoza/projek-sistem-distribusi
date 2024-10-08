@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Daftar Pelanggan </h4>
                    @role('kepala gudang')
                    <div>
                        <a href="{{ route('pelanggan.create') }}" class="btn btn-success btn-sm"><i
                                class="material-icons text-sm me-2">add</i>Tambah Data</a>       
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
                                        <th scope="col" class="text-center">Kode Rute</th>
                                        <th scope="col" class="text-center">Nama Pelanggan</th>
                                        <th scope="col" class="text-center">Alamat</th>
                                        <th scope="col" class="text-center">No telpon</th>
                                        <th scope="col" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pelanggan as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $item->name }}</td>
                                        <td class="text-center">{{ $item->pelanggan }}</td>
                                        <td class="text-center">{{ $item->alamat }}</td>
                                        <td class="text-center">{{ $item->no_telpon }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('pelanggan.edit', $item->id) }}"
                                                class="btn btn-warning btn-sm"><i
                                                    class="material-icons text-sm me-2">edit</i> Edit</a>
                                            <button value="{{ route('pelanggan.destroy', $item->id) }}"
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
                        @role(['manager', 'driver'])
                        <table id="mytable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">Nomor</th>
                                    <th scope="col" class="text-center">Kode Rute</th>
                                    <th scope="col" class="text-center">Nama Pelanggan</th>
                                    <th scope="col" class="text-center">Alamat</th>
                                    <th scope="col" class="text-center">No telpon</th>
                                    {{-- <th scope="col">Aksi</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                 @forelse ($pelanggan as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $item->name }}</td>
                                    <td class="text-center">{{ $item->pelanggan }}</td>
                                    <td class="text-center">{{ $item->alamat }}</td>
                                    <td class="text-center">{{ $item->no_telpon }}</td>
                                    {{-- <td>
                                        <a href="{{ route('pelanggan.edit', $item->id) }}"
                                            class="btn btn-primary btn-sm"><i
                                                class="material-icons text-sm me-2">edit</i> Edit</a>
                                        <button value="{{ route('pelanggan.destroy', $item->id) }}"
                                                            class="btn btn-sm btn-danger delete"
                                                            data-toggle="tooltip" data-placement="top" title="Hapus"> <i
                                                class="material-icons text-sm me-2">delete</i> Hapus
                                        </button>   
                                    </td> --}}
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
