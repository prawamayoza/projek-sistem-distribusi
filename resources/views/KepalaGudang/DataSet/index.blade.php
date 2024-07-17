@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Daftar Data Set Distribusi') }}</span>
                    <a href="{{ route('data-set.create') }}" class="btn btn-success btn-sm"><i
                            class="material-icons text-sm me-2">add</i>Tambah Data</a>
                </div>

                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table id="mytable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Tanggal Distribusi</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                 @forelse ($distribusi as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->tanggal}} </td>
                                    <td>
                                        <a href="{{ route('data-set.edit', $item->id) }}"
                                            class="btn btn-primary btn-sm"><i
                                                class="material-icons text-sm me-2">edit</i> Edit</a>
                                        <button value="{{ route('data-set.destroy', $item->id) }}"
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
