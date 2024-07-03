<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
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
        $request->validate([
            'name'                  => 'required',
            'alamat'                => 'required',
            'jumlah'                => 'required',
        ], [
            'name.required'            => 'Nama Pelanggan Wajib Dipilih',
            'alamat.required'          => 'Alamat Pelanggan Wajib Diisi',
            'jumlah.required'          => 'Jumlah Wajib Diisi',
        ]);

        Pelanggan::create([
            'name'          => $request->name,
            'alamat'    => $request->alamat,
            'jumlah'      => $request->jumlah,
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
        return view('KepalaGudang.pelanggan.form', [
            'pelanggan'     => $pelanggan,
            'title'         => 'Edit Pelanggan'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'                  => 'required',
            'alamat'                => 'required',
            'jumlah'                => 'required',
        ], [
            'name.required'            => 'Nama Pelanggan Wajib Dipilih',
            'alamat.required'          => 'Alamat Pelanggan Wajib Diisi',
            'jumlah.required'          => 'Jumlah Wajib Diisi',
        ]);
        $pelanggan   = Pelanggan::findOrFail($id);
        $data       = [
            'name'          => $request->name,
            'alamat'        => $request->alamat,
            'jumlah'        => $request->jumlah,
        ];
        $pelanggan->update($data);
        return redirect()->route('pelanggan.index')->with('success', 'Data Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pelanggan = Pelanggan::find($id);

        $pelanggan->delete();

        return response()->json(['status' => 'Data Telah Dihapus']);
    }
}
