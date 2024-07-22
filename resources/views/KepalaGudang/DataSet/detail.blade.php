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

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Pelanggan</th>
                                    <th>Jarak Gudang (KM)</th>
                                    @foreach ($customers as $customer)
                                        <th>{{ $customer->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $customer)
                                    <tr>
                                        <th scope="row">{{ $customer->name }}</th>
                                        <td>
                                            @php
                                                $warehouseDistance = $jarakGudang->firstWhere('customer_id', $customer->id);
                                            @endphp
                                            {{ $warehouseDistance ? $warehouseDistance->distance : '0' }}
                                        </td>
                                        @foreach ($customers as $otherCustomer)
                                            @if ($customer->id == $otherCustomer->id)
                                                <td>-</td>
                                            @else
                                                @php
                                                    $distance = $jarakPelanggan->firstWhere(function($item) use ($customer, $otherCustomer) {
                                                        return ($item->from_customer == $customer->id && $item->to_customer == $otherCustomer->id) ||
                                                               ($item->from_customer == $otherCustomer->id && $item->to_customer == $customer->id);
                                                    });
                                                @endphp
                                                <td>{{ $distance ? $distance->distance : '0' }}</td>
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
@endsection
