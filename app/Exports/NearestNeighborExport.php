<?php
namespace App\Exports;

use App\Models\Distribusi;
use App\Models\JarakGudang;
use App\Models\JarakPelanggan;
use App\Models\Kendaraan;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\Saving;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;

class NearestNeighborExport implements FromView, ShouldAutoSize
{
    protected $distribusi;

    public function __construct($distribusi)
    {
        $this->distribusi = $distribusi;
    }

    public function view(): View
    {
        // Retrieve the necessary data
        $distribusi = $this->distribusi;
        $savings = Saving::where('distribusi_id', $distribusi->id)->get();
        $customerIds = $savings->pluck('from_customer')->merge($savings->pluck('to_customer'))->unique();
        $customers = Pelanggan::whereIn('id', $customerIds)->get();
        $savingsWithTotals = [];
        $totalOrders = [];

        foreach ($savings as $saving) {
            $totalFromCustomer = Pesanan::where('pelanggan_id', $saving->from_customer)
                ->whereDate('tanggal', $distribusi->tanggal)
                ->sum('total');

            $totalToCustomer = Pesanan::where('pelanggan_id', $saving->to_customer)
                ->whereDate('tanggal', $distribusi->tanggal)
                ->sum('total');

            $savingsWithTotals[$saving->from_customer][$saving->to_customer] = [
                'savings' => $saving->savings,
                'total_from_customer' => $totalFromCustomer,
                'total_to_customer' => $totalToCustomer
            ];
        }

        foreach ($customers as $customer) {
            $totalOrders[$customer->id] = Pesanan::where('pelanggan_id', $customer->id)
                ->whereDate('tanggal', $distribusi->tanggal)
                ->sum('total');
        }

        $groupedRoutes = $this->groupRoutes($savingsWithTotals, $totalOrders);
        $jarakGudang = JarakGudang::where('distribusi_id', $distribusi->id)->get();
        $nearestRoutes = [];
        foreach ($groupedRoutes as $route) {
            $truckName = $route['truck_name'];
            foreach ($route['points'] as $point) {
                $fromCustomer = Pelanggan::where('name', $point['location'])->first();
                if ($fromCustomer) {
                    $distance = $jarakGudang->firstWhere('from_customer', $fromCustomer->id);
                    $nearestRoutes[$truckName][] = [
                        'location' => $point['location'],
                        'distance' => $distance ? $distance->distance : 0
                    ];
                }
            }
        }

        $smallestDistances = [];
        foreach ($nearestRoutes as $truckName => $points) {
            $minDistance = null;
            $minLocation = null;
            foreach ($points as $point) {
                if (is_null($minDistance) || $point['distance'] < $minDistance) {
                    $minDistance = $point['distance'];
                    $minLocation = $point['location'];
                }
            }
            $smallestDistances[$truckName] = [
                'location' => $minLocation,
                'distance' => $minDistance
            ];
        }

        $remainingDistances = [];
        foreach ($nearestRoutes as $truckName => $points) {
            $visitedLocations = collect([$smallestDistances[$truckName]['location']]);
            $currentLocation = $smallestDistances[$truckName]['location'];

            while ($visitedLocations->count() < count($points)) {
                $minDistance = null;
                $nextLocation = null;

                foreach ($points as $point) {
                    if (!$visitedLocations->contains($point['location'])) {
                        $fromCustomer = Pelanggan::where('name', $currentLocation)->first();
                        $toCustomer = Pelanggan::where('name', $point['location'])->first();

                        if ($fromCustomer && $toCustomer) {
                            $distanceRecord = JarakPelanggan::where('from_customer', $fromCustomer->id)
                                ->where('to_customer', $toCustomer->id)
                                ->first();

                            if (!$distanceRecord) {
                                $distanceRecord = JarakPelanggan::where('from_customer', $toCustomer->id)
                                    ->where('to_customer', $fromCustomer->id)
                                    ->first();
                            }

                            $distanceValue = $distanceRecord ? $distanceRecord->distance : 0;

                            if (is_null($minDistance) || $distanceValue < $minDistance) {
                                $minDistance = $distanceValue;
                                $nextLocation = $point['location'];
                            }
                        }
                    }
                }

                if ($nextLocation) {
                    $remainingDistances[$truckName][] = [
                        'from_location' => $currentLocation,
                        'to_location' => $nextLocation,
                        'distance' => $minDistance
                    ];
                    $visitedLocations->push($nextLocation);
                    $currentLocation = $nextLocation;
                } else {
                    break;
                }
            }
        }

        return view('KepalaGudang.DataSet.exports', [
            'distribusi' => $distribusi,
            'groupedRoutes' => $groupedRoutes,
            'remainingDistances' => $remainingDistances,
            'smallestDistances' => $smallestDistances,
        ]);
    }

    private function groupRoutes($savingsWithTotals, $totalOrders)
    {
        $trucks = Kendaraan::where('status', 'Available')->get();
        $routes = [];
        $visitedCustomers = [];

        foreach ($trucks as $truck) {
            $remainingCapacity = $truck->kapasitas;
            $routePoints = [];

            $sortedSavings = collect($savingsWithTotals)->flatMap(function ($toCustomers, $fromCustomer) {
                return collect($toCustomers)->map(function ($data, $toCustomer) use ($fromCustomer) {
                    return [
                        'from_customer' => $fromCustomer,
                        'to_customer' => $toCustomer,
                        'savings' => $data['savings'],
                        'total_from_customer' => $data['total_from_customer']
                    ];
                });
            })->sortByDesc('savings');

            foreach ($sortedSavings as $saving) {
                $fromCustomer = $saving['from_customer'];
                $totalDemand = $totalOrders[$fromCustomer] ?? 0;

                if (!in_array($fromCustomer, $visitedCustomers) && $totalDemand > 0 && $totalDemand <= $remainingCapacity) {
                    $routePoints[] = [
                        'distance' => $saving['savings'],
                        'location' => Pelanggan::find($fromCustomer)->name,
                        'demand' => $totalDemand
                    ];
                    $remainingCapacity -= $totalDemand;
                    $visitedCustomers[] = $fromCustomer;
                }
            }

            if (!empty($routePoints)) {
                $routes[] = [
                    'truck_name' => $truck->name,
                    'truck_capacity' => $truck->kapasitas,
                    'jarakPerliter' => $truck->jarakPerliter,
                    'points' => $routePoints,
                    'total_demand' => $truck->kapasitas - $remainingCapacity
                ];
            }
        }
        return $routes;
    }
}
