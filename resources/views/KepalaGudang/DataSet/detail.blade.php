@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4">
                        <span class="text-muted fw-light">
                            <a href="{{ route('data-set.index') }}" class="btn btn-icon">
                                <i class="material-icons opacity-10">arrow_back</i>
                            </a>
                        </span>
                        {{ $title }}
                    </h4>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
