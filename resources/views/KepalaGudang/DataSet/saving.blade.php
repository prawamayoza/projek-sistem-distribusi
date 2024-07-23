<!-- resources/views/saving_matrix/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Saving Matrix for</h1>
    <table class="table matrix-table">
        <thead>
            <tr>
                <th>From / To</th>
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
                    <th> {{ $totalOrders[$fromCustomer->id] ?? 0 }}</th>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('styles')
<style>
    .matrix-table {
        width: 100%;
        border-collapse: collapse;
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
</style>
@endpush

@endsection
