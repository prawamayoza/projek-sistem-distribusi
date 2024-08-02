@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Daftar Kendaraan </h4>
                    <a href="{{ route('kendaraan.create') }}" class="btn btn-success btn-sm"><i
                            class="material-icons text-sm me-2">add</i>Tambah Data</a>
                </div>

                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table id="mytable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">Nomor</th>
                                    <th scope="col" class="text-center">Nama</th>
                                    <th scope="col" class="text-center">Kapasitas</th>
                                    <th scope="col" class="text-center">Jarak Tempuh (KM)/Liter</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                 @forelse ($kendaraan as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $item->name }}</td>
                                    <td class="text-center">{{ $item->kapasitas}} </td>
                                    <td class="text-center">{{ $item->jarakPerliter}} </td>
                                    <td class="text-center">
                                        @if($item->status === 'Available')
                                            <span class="badge bg-gradient-success">{{$item->status}}</span>
                                        @else
                                            <span class="badge bg-gradient-danger">{{$item->status}}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('kendaraan.edit', $item->id) }}"
                                            class="btn btn-warning btn-sm"><i
                                                class="material-icons text-sm me-2">edit</i> Edit</a>
                                        <button value="{{ route('kendaraan.destroy', $item->id) }}"
                                                            class="btn btn-sm btn-danger delete"
                                                            data-toggle="tooltip" data-placement="top" title="Hapus"> <i
                                                class="material-icons text-sm me-2">delete</i> Hapus
                                        </button>   
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data ditemukan</td>
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
