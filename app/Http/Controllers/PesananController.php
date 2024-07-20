<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $pelanggan_id = $request->query('pelanggan_id');

        // Anda dapat menambahkan logika tambahan jika diperlukan
        return view('KepalaGudang.pesanan.form',[
            'pelanggan_id'  => $pelanggan_id,
            'title'         => 'Tambah Pesanan'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data yang diterima
        $validatedData = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'produk.*' => 'required|string|max:255',
            'jumlah.*' => 'required|integer|min:1',
        ]);

        // Loop through each order item and save
        foreach ($request->produk as $index => $produk) {
            $pesanan = new Pesanan();
            $pesanan->pelanggan_id = $request->pelanggan_id;
            $pesanan->produk = $produk;
            $pesanan->jumlah = $request->jumlah[$index];
            $pesanan->save();
        }

        // Redirect dengan pesan sukses
        return redirect()->route('pelanggan.show', $request->pelanggan_id)
                         ->with('success', 'Pesanan berhasil ditambahkan');
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
    public function edit($id)
    {
        $pesanan = Pesanan::findOrFail($id);
    
        // Assuming $pelanggan_id is part of the Pesanan model or is retrievable from it.
        $pelanggan_id = $pesanan->pelanggan_id;
    
        return view('KepalaGudang.pesanan.form', [
            'pesanan'       => $pesanan,
            'pelanggan_id'  => $pelanggan_id,
            'title'         => 'Edit Pesanan'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'produk.*' => 'required|string|max:255',
            'jumlah.*' => 'required|integer|min:1',
        ]);

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->pelanggan_id = $request->pelanggan_id;
        
        // Delete existing items
        $pesanan->delete();

        foreach ($request->produk as $index => $produk) {
            $pesanan->create([
                'pelanggan_id' => $pesanan,
                'produk' => $produk,
                'jumlah' => $request->jumlah[$index],
            ]);
        }

        return redirect()->route('pelanggan.show', $request->pelanggan_id)
                         ->with('success', 'Pesanan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
