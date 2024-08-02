<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kendaraan = Kendaraan::orderByDesc('created_at')->get();
        return view('KepalaGudang.kendaraan.index',[
            'kendaraan'     => $kendaraan,
            'title'         => 'Kelola Kendaraan'
        ]);
    }

    //ubah status button
    // KendaraanController.php

    public function changeStatus(Request $request, $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        $kendaraan->status = $request->status === 'Available' ? 'Available' : 'Non Available';
        $kendaraan->save();
    
        return response()->json(['success' => true]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('KepalaGudang.kendaraan.form', [
            'title'         => 'Tambah Kendaraan'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'                      => 'required',
            'kapasitas'                 => 'required',
            'jarakPerliter'             => 'required',
            'status'                    => 'required',


        ], [
            'name.required'                     => 'Nama Pelanggan Wajib Dipilih',
            'kapasitas.required'                => 'Kapasitas Wajib Diisi',
            'jarakPerliter.required'            => 'Jarak Tempuh Wajib Diisi',
        ]);

        Kendaraan::create([
            'name'              => $request->name,
            'kapasitas'         => $request->kapasitas,
            'jarakPerliter'     => $request->jarakPerliter,
            'status'            => $request->status,

        ]);
        return redirect()->route('kendaraan.index')->with('success', 'Data Berhasil Ditambah');

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
        $kendaraan = Kendaraan::findOrFail($id);
        return view('KepalaGudang.kendaraan.form', [
            'kendaraan'     => $kendaraan,
            'title'         => 'Edit Kendaraan'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'                      => 'required',
            'kapasitas'                 => 'required',
            'jarakPerliter'             => 'required',
            'status'                    => 'required',


        ], [
            'name.required'                     => 'Nama Pelanggan Wajib Dipilih',
            'kapasitas.required'                => 'Kapasitas Wajib Diisi',
            'jarakPerliter.required'            => 'Jarak Tempuh Wajib Diisi',
            'status.required'                   => 'Status Wajib Diisi',
        ]);
        $kendaraan   = Kendaraan::findOrFail($id);
        $data       = [
            'name'              => $request->name,
            'kapasitas'         => $request->kapasitas,
            'jarakPerliter'     => $request->jarakPerliter,
            'status'            => $request->status,

        ];
        $kendaraan->update($data);
        return redirect()->route('kendaraan.index')->with('success', 'Data Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kendaraan = Kendaraan::find($id);

        $kendaraan->delete();

        return response()->json(['status' => 'Data Telah Dihapus']);
    }
}
