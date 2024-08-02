<?php

namespace App\Exports;

use App\Models\Distribusi;
use App\Models\JarakGudang;
use App\Models\JarakPelanggan;
use App\Models\Kendaraan;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\Saving;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class NearestNeighborExport implements FromArray, WithHeadings, ShouldAutoSize, WithTitle
{
    protected $distribusi;

    public function __construct($distribusi)
    {
        $this->distribusi = $distribusi;
    }

    public function array(): array
    {
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
        $exportData = [];

        foreach ($groupedRoutes as $route) {
            $truckName = $route['truck_name'];
            foreach ($route['points'] as $point) {
                $fromCustomer = Pelanggan::where('name', $point['location'])->first();
                if ($fromCustomer) {
                    $distance = $jarakGudang->firstWhere('from_customer', $fromCustomer->id);
                    $exportData[] = [
                        'Truck Name' => $truckName,
                        'Location' => $point['location'],
                        'Distance' => $distance ? $distance->distance : 0,
                        'Demand' => $point['demand']
                    ];
                }
            }
        }

        return $exportData;
    }

    public function headings(): array
    {
        return [
            'Truck Name',
            'Location',
            'Distance',
            'Demand'
        ];
    }

    public function title(): string
    {
        return "Biaya Transportasi Metode Nearest Neighbors";
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
