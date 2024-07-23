@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            
            <h4 class="mb-0"><a href="{{ route('data-set.index') }}" class="btn btn-icon">
                <i class="material-icons opacity-10">arrow_back</i>
            </a>Saving Matrix {{$distribusi->name}}</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table matrix-table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th> </th>
                            @foreach($customers as $customer)
                                <th>{{ $customer->name }}</th>
                            @endforeach
                            <th>Permintaan (Box)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $fromCustomer)
                            <tr>
                                <th>{{ $fromCustomer->name }}</th>
                                @foreach($customers as $toCustomer)
                                    <td>
                                        @php
                                            $saving = $savingsWithTotals[$fromCustomer->id][$toCustomer->id] ?? null;
                                        @endphp
                                        @if($saving)
                                            {{ $saving['savings'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endforeach
                                <th>{{ $totalOrders[$fromCustomer->id] ?? 0 }}</th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .matrix-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .matrix-table th, .matrix-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    .matrix-table th {
        background-color: #f4f4f4;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .matrix-table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .card {
        margin-top: 20px;
    }

    .card-header {
        background-color: #f4f4f4;
    }

    .card-body {
        padding: 20px;
    }

    h1 {
        font-size: 24px;
        margin-bottom: 20px;
    }
</style>
@endpush

@endsection
