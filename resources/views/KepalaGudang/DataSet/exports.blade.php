<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Biaya Transportasi</title>
</head>
<body>
    <h2>{{ $distribusi->name }}</h2>
    <p>Tanggal Distribusi: {{ \Carbon\Carbon::parse($distribusi->tanggal)->format('Y-m-d') }}</p>
    
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
</body>
</html>
