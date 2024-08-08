<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pelanggan = Pelanggan::orderByDesc('created_at')->get();
        return view('KepalaGudang.pelanggan.index',[
            'pelanggan'     => $pelanggan,
            'title'         => 'Kelola Pelanggan'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('KepalaGudang.pelanggan.form', [
            'title'         => 'Tambah Pelanggan'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name'          => 'required',
            'pelanggan'     => 'required',
            'alamat'        => 'required',
            'no_telpon'     => 'required',

        ], [
            'name.required'             => 'Kode Rute Wajib Dipilih',
            'pelanggan.required'        => 'Nama Pelanggan Wajib Dipilih',
            'alamat.required'           => 'Alamat Pelanggan Wajib Diisi',
            'no_telepon.required'       => 'No telpon Pelanggan Wajib Diisi',

        ]);

        // Create a new Pelanggan
            Pelanggan::create([
            'name'          => $request->name,
            'pelanggan'     => $request->pelanggan,
            'alamat'        => $request->alamat,
            'no_telpon'     => $request->no_telpon,

        ]);

        return redirect()->route('pelanggan.index')->with('success', 'Data Berhasil Ditambah');
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
        $pelanggan = Pelanggan::findOrFail($id);
        $pesanan   = Pesanan::where('pelanggan_id', $id)->orderBy('created_at', 'desc')->get();
        return view('KepalaGudang.pelanggan.form', [
            'pesanan'       => $pesanan,
            'pelanggan'     => $pelanggan,
            'title'         => 'Edit Pelanggan'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pelanggan $pelanggan)
    {
        // Validate the request
        $request->validate([
            'name'          => 'required',
            'pelanggan'     => 'required',
            'alamat'        => 'required',
            'no_telpon'     => 'required',

        ], [
            'name.required'             => 'Kode Rute Wajib Dipilih',
            'pelanggan.required'        => 'Nama Pelanggan Wajib Dipilih',
            'alamat.required'           => 'Alamat Pelanggan Wajib Diisi',
            'no_telepon.required'       => 'No telpon Pelanggan Wajib Diisi',
        ]);

        // Update the Pelanggan
        $pelanggan->update([
            'name'      => $request->name,
            'pelanggan' => $request->pelanggan,
            'alamat'    => $request->alamat,
            'no_telpon'=> $request->no_telpon
        ]);
        return redirect()->route('pelanggan.index')->with('success', 'Data Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pelanggan = Pelanggan::find($id);

        if ($pelanggan) {
            // Delete related orders first
            $pelanggan->pesanan()->delete();

            // Delete the customer
            $pelanggan->delete();

            return response()->json(['status' => 'Data Telah Dihapus']);
        }

        return response()->json(['status' => 'Pelanggan tidak ditemukan'], 404);
    }
}
