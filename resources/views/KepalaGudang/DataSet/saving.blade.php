@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">
                <a href="{{ route('data-set.index') }}" class="btn btn-icon">
                    <i class="material-icons opacity-10">arrow_back</i>
                </a> 
                Hasil Perhitungan {{$distribusi->name}}
            </h4>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="saving-matrix-tab" data-toggle="tab" href="#saving-matrix" role="tab" aria-controls="saving-matrix" aria-selected="true">Saving Matrix</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="plan-tab" data-toggle="tab" href="#plan" role="tab" aria-controls="plan" aria-selected="false">Pengelompokan Rute</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="plan-tab" data-toggle="tab" href="#nearest" role="tab" aria-controls="nearest" aria-selected="false">Nearest Neighboar</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="plan-tab" data-toggle="tab" href="#jarak" role="tab" aria-controls="jarak" aria-selected="false">Jarak Biaya Transportasi</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Saving Matrix Tab -->
                <div class="tab-pane fade show active" id="saving-matrix" role="tabpanel" aria-labelledby="saving-matrix-tab">
                    <h5>Saving Matrix</h5>
                    <div class="table-responsive">
                        <table class="table matrix-table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th></th>
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
                <div class="tab-pane fade" id="plan" role="tabpanel" aria-labelledby="plan-tab">
                    <h5>Pengelompokan Rute Yang Akan Dituju</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Truk</th>
                                    <th>Kapasitas (Box)</th>
                                    <th>Jarak (KM)</th>
                                    <th>Titik</th>
                                    <th>Permintaan (Box)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupedRoutes as $route)
                                    <tr>
                                        <td rowspan="{{ count($route['points']) + 1 }}">{{ $route['truck_name'] }}</td>
                                        <td rowspan="{{ count($route['points']) + 1 }}">{{ $route['truck_capacity'] }}</td>
                                        @foreach($route['points'] as $index => $point)
                                            @if ($index > 0)
                                                <tr>
                                            @endif
                                            <td>{{ $point['distance'] }}</td>
                                            <td>{{ $point['location'] }}</td>
                                            <td>{{ $point['demand'] }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="2" class="text-center">TOTAL</td>
                                            <td class="total-cell">{{ $route['total_demand'] }}</td>
                                        </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="nearest" role="tabpanel" aria-labelledby="plan-tab">
                    <h5>Penentuan Titik Terdekat Dengan Gudang</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Truk</th>
                                    <th>Jarak dari Gudang ke Titik (KM)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nearestRoutes as $truckName => $routes)
                                <tr>
                                    <td rowspan="{{ count($routes) }}">{{ $truckName }}</td>
                                    @php
                                        $minDistance = min(array_column($routes, 'distance'));
                                    @endphp
                                    @foreach($routes as $index => $route)
                                        @if ($index > 0)
                                            <tr>
                                        @endif
                                        <td @if($route['distance'] == $minDistance) style="font-weight:bold;" @endif>
                                            {{ $route['distance'] ?? 0 }} km - {{ $route['location'] }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                
                    <h5>Detail Iterasi Jarak</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Truk</th>
                                    <th>Dari Lokasi</th>
                                    <th>Ke Lokasi</th>
                                    <th>Jarak (KM)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($remainingDistances as $truckName => $distances)
                                <tr>
                                    <td rowspan="{{ count($distances) }}">{{ $truckName }}</td>
                                    @foreach($distances as $index => $distance)
                                        @if ($index > 0)
                                            <tr>
                                        @endif
                                        <td>{{ $distance['from_location'] }}</td>
                                        <td>{{ $distance['to_location'] }}</td>
                                        <td>{{ $distance['distance'] }} km</td>
                                    </tr>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="tab-pane fade" id="jarak" role="tabpanel" aria-labelledby="plan-tab">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>Biaya Transportasi Metode Nearest Neighbors</h5>
                        <a href="{{ route('export.nearest.neighbors', ['id' => $distribusi->id]) }}" class="btn btn-success btn-sm">
                            <i class="material-icons text-sm me-2">import_export</i>Export Data
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Truk</th>
                                    <th>Rute Tempuh</th>
                                    <th>Total Jarak (KM)</th>
                                    <th>Pemakaian BBM (Liter)</th>
                                    <th>Harga Solar/Liter</th>
                                    <th>Total (Rp)</th>
                                    <th>Kapasitas Muatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($remainingDistances as $truckName => $routes)
                                    @php
                                        // Temukan data yang sesuai untuk nama truk di groupedRoutes
                                        $groupedRoute = collect($groupedRoutes)->firstWhere('truck_name', $truckName);
                                        $totalDemand = $groupedRoute['total_demand'] ?? '-';
                                        $fuelUsedPerKm = $groupedRoute['jarakPerliter'] ?? 0;
                
                                        // Generate route string and total distance
                                        $routeStr = 'G-';
                                        $routeStr .= $smallestDistances[$truckName]['location'] . '-';
                                        $totalDistance = $smallestDistances[$truckName]['distance'];
                                        foreach ($routes as $route) {
                                            $routeStr .= $route['to_location'] . '-';
                                            $totalDistance += $route['distance'];
                                        }
                                        $routeStr .= 'G';
                
                                        // Hitung pemakaian BBM total dan total biaya
                                        $fuelUsage = $totalDistance / $fuelUsedPerKm; // Total pemakaian BBM (Liter)
                                        $fuelPricePerLiter = 6800; // Harga solar per liter dalam Rupiah
                                        $totalCost = $fuelUsage * $fuelPricePerLiter; // Total biaya dalam Rupiah
                                    @endphp
                                    <tr>
                                        <td>{{ $truckName }}</td>
                                        <td>{{ $routeStr }}</td>
                                        <td>{{ number_format($totalDistance, 1) }} KM</td>
                                        <td>{{ number_format($fuelUsage, 2) }} Liter</td>
                                        <td> Rp. {{ number_format($fuelPricePerLiter, 0) }}</td>
                                        <td> Rp. {{ number_format($totalCost, 0) }}</td>
                                        <td>{{ $totalDemand }} (Box)</td>
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
    .matrix-table {
        width: 100%;
        border-collapse: collapse; /* Pastikan border collapse diatur dengan benar */
        margin-bottom: 20px;
    }

    .matrix-table th, .matrix-table td {
        border: 1px solid #ddd; /* Pastikan border sama di semua sel */
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

    .table-bordered {
        width: 100%;
        border: 1px solid #dee2e6; /* Pastikan border sama untuk tabel lainnya */
    }

    .table-bordered th, .table-bordered td {
        border: 1px solid #dee2e6; /* Konsistensi border di semua sel */
        text-align: center;
        padding: 8px;
    }

    .table-bordered th {
        background-color: #f4f4f4;
        font-weight: bold;
    }

    .table-bordered td {
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

    .nav-tabs {
        margin-bottom: 20px;
    }

    .tab-content {
        margin-top: 20px;
    }

    .total-cell {
        background-color: #10ff47; /* Warna hijau muda */
        font-weight: bold;
    }
</style>

@endpush

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
