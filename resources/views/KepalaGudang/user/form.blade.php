@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="w-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('user.index') }}"
                                class="btn btn-icon">
                                <i class="material-icons opacity-10">arrow_back</i>
                            </a>
                            @if (@$user->exists)
                                Edit
                                @php
                                    $aksi = 'Save';
                                @endphp
                            @else
                                Tambah
                                @php
                                    $aksi = 'Save';
                                @endphp
                            @endif
                            User
                    </h4>
                    @if (@$user->exists)
                        <form id="myForm" class="forms-sample" enctype="multipart/form-data" method="POST"
                            action="{{ route('user.update', $user) }}">
                            @method('put')
                        @else
                            <form id="myForm" class="forms-sample" enctype="multipart/form-data" method="POST"
                                action="{{ route('user.store') }}">
                    @endif
                    {{ csrf_field() }}

                        <div class="input-group input-group-dynamic mb-4">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Nama</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', @$user->name) }}" required autocomplete="name" autofocus placeholder="Nama" aria-label="Nama" aria-describedby="basic-addon1">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="input-group input-group-dynamic mb-4">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', @$user->email) }}" required autocomplete="email" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="input-group input-group-dynamic mb-4">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="input-group input-group-dynamic mb-4">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Konfirmasi Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="konfirmasi password" aria-label="konfirmasi password" aria-describedby="basic-addon1">
                            </div>
                        </div>

                        <div class="input-group input-group-dynamic mb-4">
                            <label for="role" class="col-md-4 col-form-label text-md-right">Role</label>

                            <div class="col-md-6">
                                <select name="role" class="form-control selectric @error('role') is-invalid @enderror">
                                    <option value="" selected disabled>Pilih Hak Akses</option>
                                
                                    @forelse ($role as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('role', isset($user) && $user->hasRole($item->id)) == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                    @empty
                                        <option value="{{ $item->id }}"
                                            {{ old('role', isset($user) ? $user->role : '') == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforelse
                                </select>
                                
                                
                                @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0 justify-content-end">
                            <div class="col-md-6 text-right">
                                <button type="submit" class="btn btn-warning" id="submitButton">
                                {{ $aksi}} <i class="material-icons opacity-10">save</i>
                                    <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                    </form>

                    <script>
                        document.getElementById('myForm').addEventListener('submit', function() {
                            var submitButton = document.getElementById('submitButton');
                            var loadingSpinner = document.getElementById('loadingSpinner');
                            submitButton.disabled = true;
                            loadingSpinner.classList.remove('d-none');
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
