<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Biaya Transportasi</title>
</head>
<body>
    @foreach($exportData as $data)
        <h2>{{ $data['distribusi']->name }}</h2>
        <p>Tanggal Distribusi: {{ \Carbon\Carbon::parse($data['distribusi']->tanggal)->format('Y-m-d') }}</p>
        
        <table border="1">
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
                @foreach($data['remainingDistances'] as $truckName => $routes)
                    @php
                        $groupedRoute = collect($data['groupedRoutes'])->firstWhere('truck_name', $truckName);
                        $totalDemand = $groupedRoute['total_demand'] ?? '-';
                        $fuelUsedPerKm = $groupedRoute['jarakPerliter'] ?? 0;
        
                        $routeStr = 'G-';
                        $routeStr .= $data['smallestDistances'][$truckName]['location'] . '-';
                        $totalDistance = $data['smallestDistances'][$truckName]['distance'];
                        foreach ($routes as $route) {
                            $routeStr .= $route['to_location'] . '-';
                            $totalDistance += $route['distance'];
                        }
                        $routeStr .= 'G';
        
                        $fuelUsage = $totalDistance / $fuelUsedPerKm;
                        $fuelPricePerLiter = 6800;
                        $totalCost = $fuelUsage * $fuelPricePerLiter;
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
        <hr>
    @endforeach
</body>
</html>
