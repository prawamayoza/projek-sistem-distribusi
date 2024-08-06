<?php

namespace App\Http\Controllers;

use App\Models\Distribusi;
use App\Models\Kendaraan;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $login = auth()->user();

        // Ambil data berdasarkan peran pengguna
        if ($login->hasRole('driver')) {
            $pesanan = Pesanan::whereDate('tanggal', now()->toDateString())->sum('total');
        } else {
            $pesanan = Pesanan::all()->sum('total');
        }
        $distribusi     = Distribusi::where('status', 'Waiting')->count();
        $user           = User::all()->count();
        $pelanggan      = Pelanggan::all()->count();
        $kendaraan      = Kendaraan::where('status', 'Available')->count();
        
        return view('home', [
            'pesanan'       => $pesanan,
            'distribusi'    => $distribusi,
            'kendaraan'     => $kendaraan,
            'user'          => $user,
            'pelanggan'     => $pelanggan,
            'title'         => 'CV.Bintang Berkah'
        ]);
        
    }
}
