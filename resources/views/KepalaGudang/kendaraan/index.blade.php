@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Daftar Kendaraan </h4>
                    @role('kepala gudang')
                    <a href="{{ route('kendaraan.create') }}" class="btn btn-success btn-sm"><i
                            class="material-icons text-sm me-2">add</i>Tambah Data</a>
                    @endrole
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
                                    @role('kepala gudang')
                                    <th scope="col" class="text-center">Aksi</th>
                                    @endrole
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kendaraan as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $item->name }}</td>
                                    <td class="text-center">{{ $item->kapasitas }} </td>
                                    <td class="text-center">{{ $item->jarakPerliter }} </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input toggle-status" type="checkbox"
                                                   data-id="{{ $item->id }}"
                                                   id="statusSwitch{{ $item->id }}"
                                                   {{ $item->status === 'Available' ? 'checked' : '' }}>
                                            <label class="form-check-label {{ $item->status === 'Available' ? 'text-success' : 'text-danger' }}"
                                                   for="statusSwitch{{ $item->id }}">{{ $item->status }}</label>
                                        </div>
                                    </td>
                                    @role('kepala gudang')
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
                                    @endrole
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data ditemukan</td>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleStatusButtons = document.querySelectorAll('.toggle-status');

        toggleStatusButtons.forEach(button => {
            button.addEventListener('change', function () {
                const kendaraanId = this.getAttribute('data-id');
                const isChecked = this.checked;
                
                fetch(`/kendaraan/${kendaraanId}/changeStatus`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status: isChecked ? 'Available' : 'Unavailable' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const label = this.nextElementSibling;
                        label.textContent = isChecked ? 'Available' : 'Unavailable';
                        label.classList.toggle('text-success', isChecked);
                        label.classList.toggle('text-danger', !isChecked);
                    } else {
                        this.checked = !isChecked; // Revert the toggle switch if the request failed
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.checked = !isChecked; // Revert the toggle switch if there is an error
                });
            });
        });
    });
</script>
@endsection
