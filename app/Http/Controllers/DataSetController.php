<?php

namespace App\Http\Controllers;

use App\Exports\AllNearestNeighborExport;
use App\Exports\NearestNeighborExport;
use App\Models\Distribusi;
use App\Models\JarakGudang;
use App\Models\JarakPelanggan;
use App\Models\Kendaraan;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\Saving;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DataSetController extends Controller
{
    protected $data;

    public function __construct()
    {
        // Initialize $data or fetch it from the database
        $this->data = []; // Replace with actual data fetching logic
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
    
        // Ambil data berdasarkan peran pengguna
        if ($user->hasRole('driver')) {
            // Hanya tampilkan data dengan status 'Approve' untuk driver
            $distribusi = Distribusi::where('status', 'Approve')->orderByDesc('created_at')->get();
        } else {
            // Tampilkan semua data untuk kepala gudang dan manager
            $distribusi =   Distribusi::orderByDesc('created_at')->get();

        }
        return view('KepalaGudang.DataSet.index',[
            'distribusi'    =>$distribusi,
            'title'         =>'Kelola Data Set'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Pelanggan::all();
        return view('KepalaGudang.DataSet.form',[
            'customers'     => $customers,
            'title'         => 'Tambahkan Data Set'
        ]);
    }
    //update status
    public function updateStatus(Request $request, $id)
    {
        $distribusi = Distribusi::find($id);
        if (!$distribusi) {
            return redirect()->back()->with('error', 'Distribusi not found.');
        }
        
        // Validate and update the status
        $validated = $request->validate([
            'status' => 'required|string|in:Approve,Waiting', // Adjust validation as needed
        ]);

        $distribusi->status = $validated['status'];
        $distribusi->save();

        return redirect()->back()->with('success', 'Status updated successfully.');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'tanggal' => 'required|date',
            'from_customer.*' => 'required|exists:pelanggans,id',
            'to_customer.*' => 'required|exists:pelanggans,id',
            'distance.*' => 'required|numeric',
            'customer_to_warehouse.*' => 'required|exists:pelanggans,id',
            'warehouse_distance.*' => 'required|numeric',
        ]);
    
        // Custom validation to check for duplicate `from_customer` and `to_customer` pairs
        $fromCustomers = $request->input('from_customer');
        $toCustomers = $request->input('to_customer');
        $uniquePairs = [];
        foreach ($fromCustomers as $index => $fromCustomer) {
            $pair = $fromCustomer . '-' . $toCustomers[$index];
            if (in_array($pair, $uniquePairs)) {
                return back()->withErrors(['duplicate_pair' => 'Duplicate pair of "From Customer" and "To Customer" detected.']);
            }
            $uniquePairs[] = $pair;
        }
    
        $distribusi = Distribusi::create([
            'name'      => $request->name,
            'tanggal'   => $request->tanggal,
            'status'    => 'Waiting'
        ]);
    
        $distances = [];
        for ($i = 0; $i < count($request->from_customer); $i++) {
            $distances[] = [
                'distribusi_id' => $distribusi->id,
                'from_customer' => $request->from_customer[$i],
                'to_customer' => $request->to_customer[$i],
                'distance' => $request->distance[$i],
            ];
        }
    
        foreach ($distances as $distance) {
            JarakPelanggan::create($distance);
        }
    
        $warehouseDistances = [];
        for ($i = 0; $i < count($request->customer_to_warehouse); $i++) {
            $warehouseDistances[] = [
                'distribusi_id' => $distribusi->id,
                'from_customer' => $request->customer_to_warehouse[$i],
                'distance' => $request->warehouse_distance[$i],
            ];
        }
    
        foreach ($warehouseDistances as $warehouseDistance) {
            JarakGudang::create($warehouseDistance);
        }
    
        $this->calculateSavings($distribusi->id);
    
        return redirect()->route('data-set.index')->with('success', 'Data saved successfully.');
    }
    

    public function calculateSavings($distribusiId)
    {
        $jarakGudangs = JarakGudang::where('distribusi_id', $distribusiId)->get();
        $jarakPelangans = JarakPelanggan::where('distribusi_id', $distribusiId)->get();
        
        $existingSavings = Saving::where('distribusi_id', $distribusiId)->get()->keyBy(function ($saving) {
            return $saving->from_customer . '-' . $saving->to_customer;
        });
    
        foreach ($jarakPelangans as $jp) {
            $d_ij = $jp->distance;
            
            $d_0i = $jarakGudangs->firstWhere('from_customer', $jp->from_customer)->distance;
            $d_0j = $jarakGudangs->firstWhere('from_customer', $jp->to_customer)->distance;
    
            $savingValue = $d_0i + $d_0j - $d_ij;
    
            if ($jp->from_customer === $jp->to_customer) {
                $savingValue = 0;
            }
    
            $key = $jp->from_customer . '-' . $jp->to_customer;
    
            if (isset($existingSavings[$key])) {
                $existingSavings[$key]->update([
                    'savings' => $savingValue
                ]);
            } else {
                // Create new record
                Saving::create([
                    'from_customer' => $jp->from_customer,
                    'to_customer' => $jp->to_customer,
                    'distribusi_id' => $distribusiId,
                    'savings' => $savingValue
                ]);
            }
        }
    
        $existingSavings->each(function ($saving) use ($jarakPelangans) {
            $key = $saving->from_customer . '-' . $saving->to_customer;
            if (!$jarakPelangans->contains(function ($jp) use ($key) {
                return ($jp->from_customer . '-' . $jp->to_customer) === $key;
            })) {
                $saving->delete();
            }
        });
    }
    //halaman perhitungan
    public function perhitungan($distribusiId)
    {
        // Retrieve the Distribusi record
        $distribusi = Distribusi::find($distribusiId);
        if (!$distribusi) {
            abort(404, 'Distribusi not found');
        }
    
        // Retrieve savings for the given distribusi_id
        $savings = Saving::where('distribusi_id', $distribusiId)->get();
    
        // Extract unique customer IDs from the savings
        $customerIds = $savings->pluck('from_customer')->merge($savings->pluck('to_customer'))->unique();
        $customers = Pelanggan::whereIn('id', $customerIds)->get();
    
        // Prepare data structure to hold savings and total orders
        $savingsWithTotals = [];
        $totalOrders = [];
    
        // Calculate total orders and savings for each customer pair
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
    
        // Calculate total orders for each customer
        foreach ($customers as $customer) {
            $totalOrders[$customer->id] = Pesanan::where('pelanggan_id', $customer->id)
                ->whereDate('tanggal', $distribusi->tanggal)
                ->sum('total');
        }
    
        // Group customers into routes based on highest savings and truck capacity
        $groupedRoutes = $this->groupRoutes($savingsWithTotals, $totalOrders);
    
        // Retrieve distances from `jarak_gudang` for nearest neighbor tab
        $jarakGudang = JarakGudang::where('distribusi_id', $distribusiId)->get();
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
    
        // Find the smallest distance for each truck
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
    
        // Initialize remaining distances array
        $remainingDistances = [];
    
        // Loop through each truck to find the remaining distances
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
                            // Check for distance in both directions
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
                    break; // No more unvisited locations
                }
            }
        }
        
        // Return view with the necessary data
        return view('KepalaGudang.DataSet.saving', [
            'distribusi'            => $distribusi,
            'savingsWithTotals'     => $savingsWithTotals,
            'customers'             => $customers,
            'totalOrders'           => $totalOrders,
            'groupedRoutes'         => $groupedRoutes,
            'nearestRoutes'         => $nearestRoutes, // Pass nearest routes data
            'smallestDistances'     => $smallestDistances, // Pass smallest distances data
            'remainingDistances'    => $remainingDistances, // Pass remaining distances data
            'title'                 => 'Perhitungan'
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
    
            // Flatten the savings array and sort by savings in descending order
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
    
            // Iterate over sorted savings to create routes
            foreach ($sortedSavings as $saving) {
                $fromCustomer = $saving['from_customer'];
                $totalDemand = $totalOrders[$fromCustomer] ?? 0;
    
                // Check if customer has already been visited and if the demand fits the truck capacity
                if (!in_array($fromCustomer, $visitedCustomers) && $totalDemand > 0 && $totalDemand <= $remainingCapacity) {
                    $routePoints[] = [
                        'distance' => $saving['savings'],
                        'location' => Pelanggan::find($fromCustomer)->name,
                        'demand' => $totalDemand
                    ];
                    $remainingCapacity -= $totalDemand;
                    $visitedCustomers[] = $fromCustomer; // Mark customer as visited
                }
            }
    
            if (!empty($routePoints)) {
                $routes[] = [
                    'truck_name'        => $truck->name,
                    'truck_capacity'    => $truck->kapasitas,
                    'jarakPerliter'     => $truck->jarakPerliter,
                    'points'            => $routePoints,
                    'total_demand'      => $truck->kapasitas - $remainingCapacity
                ];
            }
        }
    
        // Handle remaining customers if there are any unvisited
        $remainingCustomers = array_diff(array_keys($totalOrders), $visitedCustomers);
        while (!empty($remainingCustomers)) {
            $truck = Kendaraan::where('status', 'Available')->first();
            if (!$truck) {
                break; // No more available trucks
            }
            $remainingCapacity = $truck->kapasitas;
            $routePoints = [];
    
            // Re-sort savings for remaining customers
            $sortedSavings = collect($savingsWithTotals)->flatMap(function ($toCustomers, $fromCustomer) use ($remainingCustomers) {
                if (in_array($fromCustomer, $remainingCustomers)) {
                    return collect($toCustomers)->map(function ($data, $toCustomer) use ($fromCustomer) {
                        return [
                            'from_customer' => $fromCustomer,
                            'to_customer' => $toCustomer,
                            'savings' => $data['savings'],
                            'total_from_customer' => $data['total_from_customer']
                        ];
                    });
                }
                return [];
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
                    'truck_name'        => $truck->name,
                    'truck_capacity'    => $truck->kapasitas,
                    'jarakPerliter'     => $truck->jarakPerliter,
                    'points'            => $routePoints,
                    'total_demand'      => $truck->kapasitas - $remainingCapacity
                ];
            }
            $remainingCustomers = array_diff($remainingCustomers, $visitedCustomers);
        }
    
        return $routes;
    }

    public function exportNearestNeighbors($id)
    {
        $distribusi = Distribusi::find($id);
    
        if ($distribusi) {
            $distribusiDate = \Carbon\Carbon::parse($distribusi->tanggal)->format('Y-m-d');
            $title = "Biaya Transportasi $distribusiDate";
            // Ensure title length does not exceed 31 characters
            $title = substr($title, 0, 31);
    
            return Excel::download(new NearestNeighborExport($distribusi), "{$title}.xlsx");
        }
    
        return redirect()->back()->withErrors('Distribusi not found');
    }
    
    public function exportdistribusi()
    {
        $distribusiList = Distribusi::all();
    
        if ($distribusiList->isNotEmpty()) {
            $title = "Biaya Transportasi Semua Distribusi";
            // Ensure title length does not exceed 31 characters
            $title = substr($title, 0, 31);
    
            return Excel::download(new AllNearestNeighborExport, "{$title}.xlsx");
        }
    
        return redirect()->back()->withErrors('No distributions found');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $distribusi = Distribusi::findOrFail($id);
        $jarakPelanggan = JarakPelanggan::where('distribusi_id', $id)->orderBy('created_at', 'desc')->get();
        $jarakGudang = JarakGudang::where('distribusi_id', $id)->orderBy('created_at', 'desc')->get();

        $customerIds = $jarakPelanggan->pluck('from_customer')
            ->merge($jarakPelanggan->pluck('to_customer'))
            ->merge($jarakGudang->pluck('customer_id'))
            ->unique()
            ->toArray();
        $customers = Pelanggan::whereIn('id', $customerIds)->get();
    
        return view('KepalaGudang.DataSet.detail', [
            'distribusi' => $distribusi,
            'jarakPelanggan' => $jarakPelanggan,
            'jarakGudang' => $jarakGudang,
            'customers' => $customers,
            'title' => 'Detail Data Set Distribusi'
        ]);
    }    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $distribusi         = Distribusi::findOrFail($id);
        $jarakPelanggan     = JarakPelanggan::where('distribusi_id', $id)->orderBy('created_at', 'desc')->get();
        $jarakGudang        = JarakGudang::where('distribusi_id', $id)->orderBy('created_at', 'desc')->get();
        $customers  = Pelanggan::all();
        return view('KepalaGudang.DataSet.form',[
            'distribusi'            => $distribusi,
            'jarakPelanggan'        => $jarakPelanggan,
            'jarakGudang'           => $jarakGudang,
            'customers'             => $customers,
            'title'                 => 'Edit Data Set Distribusi'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'tanggal' => 'required|date',
            'from_customer.*' => 'required|exists:pelanggans,id',
            'to_customer.*' => 'required|exists:pelanggans,id',
            'distance.*' => 'required|numeric',
            'customer_to_warehouse.*' => 'required|exists:pelanggans,id',
            'warehouse_distance.*' => 'required|numeric',
        ]);

        $distribusi = Distribusi::findOrFail($id);
        $distribusi->update([
            'name' => $request->name,
            'tanggal' => $request->tanggal,
        ]);

        JarakPelanggan::where('distribusi_id', $id)->delete();
        JarakGudang::where('distribusi_id', $id)->delete();

        // Siapkan data untuk tabel jarak_pelanggans
        $distances = [];
        for ($i = 0; $i < count($request->from_customer); $i++) {
            $distances[] = [
                'from_customer' => $request->from_customer[$i],
                'to_customer' => $request->to_customer[$i],
                'distance' => $request->distance[$i],
                'distribusi_id' => $distribusi->id,
            ];
        }

        foreach ($distances as $distance) {
            JarakPelanggan::create($distance);
        }

        $warehouseDistances = [];
        for ($i = 0; $i < count($request->customer_to_warehouse); $i++) {
            $warehouseDistances[] = [
                'distribusi_id' => $distribusi->id,
                'from_customer' => $request->customer_to_warehouse[$i],
                'distance' => $request->warehouse_distance[$i],
            ];
        }

        foreach ($warehouseDistances as $warehouseDistance) {
            JarakGudang::create($warehouseDistance);
        }

        $this->calculateSavings($distribusi->id);

        return redirect()->route('data-set.index')->with('success', 'Data updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $distribusi = Distribusi::find($id);
    
        if ($distribusi) {
            // Hapus semua data set yang terkait dengan distribusi ini
            JarakPelanggan::where('distribusi_id', $id)->delete();
            JarakGudang::where('distribusi_id', $id)->delete();
            
            // Hapus semua data saving yang terkait dengan distribusi ini
            Saving::where('distribusi_id', $id)->delete();
    
            // Hapus distribusi itu sendiri
            $distribusi->delete();
    
            return response()->json(['status' => 'Data Telah Dihapus']);
        }
    
        return response()->json(['status' => 'Distribusi tidak ditemukan'], 404);
    }
    
}
