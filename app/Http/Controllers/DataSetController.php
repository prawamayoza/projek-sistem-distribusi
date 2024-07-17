<?php

namespace App\Http\Controllers;

use App\Models\DataSet;
use App\Models\Distribusi;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class DataSetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $distribusi =   Distribusi::all();
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
            'name'              => 'required',
            'tanggal'           => 'required|date',
            'from_customer.*'   => 'required|exists:pelanggans,id',
            'to_customer.*'     => 'required|exists:pelanggans,id',
            'distance.*'        => 'required|numeric',
        ]);

        $distribusi = Distribusi::create([
            'name' => $request->name,
            'tanggal' => $request->tanggal,
        ]);

        $distances = [];
        for ($i = 0; $i < count($request->from_customer); $i++) {
            $distances[] = [
                'distribusi_id'     => $distribusi->id,
                'from_customer'     => $request->from_customer[$i],
                'to_customer'       => $request->to_customer[$i],
                'distance'          => $request->distance[$i],
            ];
        }
        foreach ($distances as $distance) {
            DataSet::create($distance);
        }

        return redirect()->route('data-set.index')->with('success', 'Data saved successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $distribusi = Distribusi::findOrFail($id);
        $dataSet    = DataSet::where('distribusi_id', $id)->orderBy('created_at', 'desc')->get();
        $customers  = Pelanggan::all();
        return view('KepalaGudang.DataSet.form',[
            'distribusi'    => $distribusi,
            'dataSet'       => $dataSet,
            'customers'     => $customers,
            'title'         => 'Edit Data Set Distribusi'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'              => 'required',
            'tanggal'           => 'required|date',
            'from_customer.*'   => 'required|exists:pelanggans,id',
            'to_customer.*'     => 'required|exists:pelanggans,id',
            'distance.*'        => 'required|numeric',
        ]);

        // Update data distribusi
        $distribusi = Distribusi::findOrFail($id);
        $distribusi->update([
            'name' => $request->name,
            'tanggal' => $request->tanggal,
        ]);

        // Hapus data set lama
        DataSet::where('distribusi_id', $id)->delete();

        // Siapkan data untuk tabel data_sets
        $distances = [];
        for ($i = 0; $i < count($request->from_customer); $i++) {
            $distances[] = [
                'from_customer'     => $request->from_customer[$i],
                'to_customer'       => $request->to_customer[$i],
                'distance'          => $request->distance[$i],
                'distribusi_id'     => $distribusi->id, // Simpan id distribusi
            ];
        }

        // Simpan data ke tabel data_sets
        foreach ($distances as $distance) {
            DataSet::create($distance);
        }

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
            DataSet::where('distribusi_id', $id)->delete();

            // Hapus distribusi itu sendiri
            $distribusi->delete();

            return response()->json(['status' => 'Data Telah Dihapus']);
        }

        return response()->json(['status' => 'Distribusi tidak ditemukan'], 404);
    }
}
