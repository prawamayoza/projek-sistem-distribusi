@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <a href="{{ route('data-set.index') }}" class="btn btn-icon">
                            <i class="material-icons opacity-10">arrow_back</i>
                        </a> 
                        Data Jarak {{$distribusi->name}} </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th> </th>
                                    <th>Jarak Gudang (KM)</th>
                                    @foreach ($customers as $customer)
                                        <th>{{ $customer->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $index => $customer)
                                    <tr>
                                        <th scope="row">{{ $customer->name }}</th>
                                        <td>
                                            @php
                                                $warehouseDistance = $jarakGudang->where('from_customer', $customer->id)->first();
                                            @endphp
                                            @if ($warehouseDistance)
                                                {{ $warehouseDistance->distance }}
                                            @else
                                                <span class="text-danger">No data</span>
                                            @endif
                                        </td>
                                        @foreach ($customers as $otherIndex => $otherCustomer)
                                            @if ($customer->id == $otherCustomer->id)
                                                <td>0</td>
                                            @elseif ($index < $otherIndex)
                                                @php
                                                    $distance = $jarakPelanggan->firstWhere(function($item) use ($customer, $otherCustomer) {
                                                        return ($item->from_customer == $customer->id && $item->to_customer == $otherCustomer->id) ||
                                                               ($item->from_customer == $otherCustomer->id && $item->to_customer == $customer->id);
                                                    });
                                                @endphp
                                                    <td>{{ $distance->distance }}</td>
                                            @else
                                                <td>-</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .table-bordered {
        border: 1px solid #ddd;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }

    .table th, .table td {
        text-align: center;
        vertical-align: middle;
    }

    .table thead th {
        background-color: #f4f4f4;
    }

    .text-danger {
        color: #dc3545;
    }
</style>
@endpush

@endsection
