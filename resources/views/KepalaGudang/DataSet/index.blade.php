@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Daftar Data Perhitungan Distribusi</h4>
                    <div>
                        @role('kepala gudang')
                            @php
                                // Get the last distribution item
                                $lastDistribusi = $distribusi->last();
                            @endphp
                            <a href="{{ route('data-set.create') }}" 
                               class="btn btn-success btn-sm {{ $lastDistribusi && $lastDistribusi->status !== 'Done' ? 'disabled' : '' }}">
                                <i class="material-icons text-sm me-2">add</i>Tambah Data
                            </a>
                            @role(['manager', 'kepala gudang'])
                                <a href="{{ route('export.distribusi') }}" class="btn btn-success btn-sm">
                                    <i class="material-icons text-sm me-2">import_export</i>Export Data
                                </a>
                            @endrole
                        @endrole
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table id="mytable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">Nomor</th>
                                    <th scope="col" class="text-center">Nama</th>
                                    <th scope="col" class="text-center">Tanggal Distribusi</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($distribusi as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $item->name }}</td>
                                    <td class="text-center">{{ $item->tanggal }}</td>
                                    <td class="text-center">
                                        @if($item->status === 'Approve')
                                            <span class="badge bg-gradient-success">{{ $item->status }}</span>
                                        @else
                                            <span class="badge bg-gradient-danger">{{ $item->status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('saving_matrix.show', $item->id) }}" class="btn btn-info btn-sm">
                                            <i class="material-icons text-sm me-2">calculate</i> Perhitungan
                                        </a>
                                        @role('manager')
                                            @if($item->status === 'Waiting')
                                            <form action="{{ route('data-set.update-status', $item->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="Approve">
                                                <button type="submit" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Update Status to Approve">
                                                    <i class="material-icons text-sm me-2">update</i> Approve
                                                </button>
                                            </form>
                                            @endif
                                            <a href="{{ route('data-set.show', $item->id) }}" class="btn btn-info btn-sm">
                                                <i class="material-icons text-sm me-2">remove_red_eye</i>Detail
                                            </a>
                                        @endrole
                                        @role('kepala gudang')
                                            @if($item->status === 'Approve')
                                            <form action="{{ route('data-set.update-status', $item->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="Done">
                                                <button type="submit" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Update Status to Done">
                                                    <i class="material-icons text-sm me-2">done</i> Done
                                                </button>
                                            </form>
                                            @endif
                                            <a href="{{ route('data-set.show', $item->id) }}" class="btn btn-info btn-sm">
                                                <i class="material-icons text-sm me-2">remove_red_eye</i>Detail
                                            </a>
                                            <a href="{{ route('data-set.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                                <i class="material-icons text-sm me-2">edit</i> Edit
                                            </a>
                                            <button value="{{ route('data-set.destroy', $item->id) }}" class="btn btn-sm btn-danger delete" data-toggle="tooltip" data-placement="top" title="Hapus">
                                                <i class="material-icons text-sm me-2">delete</i> Hapus
                                            </button>
                                        @endrole
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
