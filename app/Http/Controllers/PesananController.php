<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pesanan =  Pesanan::orderByDesc('created_at')->get();
        return view('KepalaGudang.pesanan.index',[
            'pesanan'       => $pesanan,
            'title'         => 'Pesanan Pelanggan'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $pelanggan = Pelanggan::all();
        return view('KepalaGudang.pesanan.form',[
            'pelanggan'     => $pelanggan,
            'title'         => 'Tambah Pesanan'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'tanggal' => 'required|date',
            'produk.*' => 'required|string',
            'jumlah.*' => 'required|integer|min:1',
        ]);
        $pesanan = new Pesanan;
        $pesanan->pelanggan_id = $request->pelanggan_id;
        $pesanan->tanggal = $request->tanggal;
        $pesanan->total = $request->total;
        $pesanan->save();
    
        foreach ($request->produk as $index => $produk) {
            $pesananProduk = new Produk(); 
            $pesananProduk->pesanan_id = $pesanan->id;
            $pesananProduk->pelanggan_id = $request->pelanggan_id; 
            $pesananProduk->produk = $produk;
            $pesananProduk->jumlah = $request->jumlah[$index];
            $pesananProduk->save();
        }
    
        return redirect()->route('pesanan.index')->with('success', 'Pesanan created successfully.');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pesanan = Pesanan::with('produk')->findOrFail($id);
        return view('KepalaGudang.pesanan.show',[
            'pesanan'       => $pesanan,
            'title'         => 'Detail Pesanan'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        
        $pesanan    = Pesanan::findOrFail($id);
        $pelanggan  = Pelanggan::all();
        $produk     = Produk::where('pesanan_id', $id)->get(); 
    
        return view('KepalaGudang.pesanan.form', [
            'pesanan'       => $pesanan,
            'pelanggan'     => $pelanggan,
            'produk'        => $produk,
            'title'         => 'Edit Pesanan'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'tanggal' => 'required|date',
            'produk.*' => 'required|string',
            'jumlah.*' => 'required|integer|min:1',
        ]);
    
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->pelanggan_id = $request->pelanggan_id;
        $pesanan->tanggal = $request->tanggal;
        $pesanan->total = $request->total;
        $pesanan->save();
    
        Produk::where('pesanan_id', $id)->delete();
    
        // Save the updated products
        foreach ($request->produk as $index => $produk) {
            $pesananProduk = new Produk();
            $pesananProduk->pesanan_id = $pesanan->id;
            $pesananProduk->pelanggan_id = $request->pelanggan_id;
            $pesananProduk->produk = $produk;
            $pesananProduk->jumlah = $request->jumlah[$index];
            $pesananProduk->save();
        }
    
        return redirect()->route('pesanan.index')->with('success', 'Pesanan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pesanan = Pesanan::find($id);

        if ($pesanan) {
            Produk::where('pesanan_id', $id)->delete();

            $pesanan->delete();

            return response()->json(['status' => 'Data Telah Dihapus']);
        }

        return response()->json(['status' => 'Distribusi tidak ditemukan'], 404);
    }
}
