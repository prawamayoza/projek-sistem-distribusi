<?php

namespace App\Http\Controllers;

use App\Models\Distribusi;
use App\Models\JarakGudang;
use App\Models\JarakPelanggan;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\Saving;
use Illuminate\Http\Request;

class DataSetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $distribusi =   Distribusi::orderByDesc('created_at')->get();
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

        $distribusi = Distribusi::create([
            'name' => $request->name,
            'tanggal' => $request->tanggal,
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
    //display perhitungan
    public function perhitungan($distribusiId)
    {
        $distribusi = Distribusi::find($distribusiId);
        if (!$distribusi) {
            abort(404, 'Distribusi not found');
        }
        
        $distributionDate = $distribusi->tanggal;
    
        $savings = Saving::where('distribusi_id', $distribusiId)->get();
    
        $customerIds = $savings->pluck('from_customer')->merge($savings->pluck('to_customer'))->unique();
        $customers = Pelanggan::whereIn('id', $customerIds)->get();
    
        $savingsWithTotals = [];
        $totalOrders = [];
    
        foreach ($savings as $saving) {
            $totalFromCustomer = Pesanan::where('pelanggan_id', $saving->from_customer)
                ->whereDate('tanggal', $distributionDate)
                ->sum('total');
            
            $totalToCustomer = Pesanan::where('pelanggan_id', $saving->to_customer)
                ->whereDate('tanggal', $distributionDate)
                ->sum('total');
            
            $savingsWithTotals[$saving->from_customer][$saving->to_customer] = [
                'savings' => $saving->savings,
                'total_from_customer' => $totalFromCustomer,
                'total_to_customer' => $totalToCustomer
            ];
        }
        
        foreach ($customers as $customer) {
            $totalOrders[$customer->id] = Pesanan::where('pelanggan_id', $customer->id)
                ->whereDate('tanggal', $distributionDate)
                ->sum('total');
        }
        
        return view('KepalaGudang.DataSet.saving', compact('savingsWithTotals', 'customers', 'totalOrders'));
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
