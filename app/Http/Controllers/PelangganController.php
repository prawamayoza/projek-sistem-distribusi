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
            'name'       => 'required',
            'alamat'     => 'required',
            'produk.*'   => 'required|string',
            'jumlah.*'   => 'required|integer',
        ], [
            'name.required'       => 'Nama Pelanggan Wajib Dipilih',
            'alamat.required'     => 'Alamat Pelanggan Wajib Diisi',
            'produk.*.required'   => 'Produk Wajib Diisi',
            'jumlah.*.required'   => 'Jumlah Wajib Diisi',
        ]);

        // Create a new Pelanggan
        $pelanggan = Pelanggan::create([
            'name'    => $request->name,
            'alamat'  => $request->alamat,
        ]);

        // Create Pesanan for the Pelanggan
        foreach ($request->produk as $index => $produk) {
            Pesanan::create([
                'pelanggan_id' => $pelanggan->id,
                'produk'       => $produk,
                'jumlah'       => $request->jumlah[$index],
            ]);
        }

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
            'name'       => 'required',
            'alamat'     => 'required',
            'produk.*'   => 'required|string',
            'jumlah.*'   => 'required|integer',
        ], [
            'name.required'       => 'Nama Pelanggan Wajib Dipilih',
            'alamat.required'     => 'Alamat Pelanggan Wajib Diisi',
            'produk.*.required'   => 'Produk Wajib Diisi',
            'jumlah.*.required'   => 'Jumlah Wajib Diisi',
        ]);

        // Update the Pelanggan
        $pelanggan->update([
            'name'    => $request->name,
            'alamat'  => $request->alamat,
        ]);

        // Remove old Pesanan
        $pelanggan->pesanan()->delete();

        // Create new Pesanan for the Pelanggan
        foreach ($request->produk as $index => $produk) {
            Pesanan::create([
                'pelanggan_id' => $pelanggan->id,
                'produk'       => $produk,
                'jumlah'       => $request->jumlah[$index],
            ]);
        }

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
