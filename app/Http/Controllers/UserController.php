<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id = Auth::user();

        if ($id->hasRole('admin')) {
            $user = User::whereHas('roles', function ($q) {
                $q->where('name', 'user');
            })->orderByDesc('created_at')->get();
        } else {
            $user = User::orderByDesc('created_at')->get();
        }
        
        return view('KepalaGudang.user.index', [
            'user'  => $user,
            'title' => "Kelola user"    
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $role = Role::all();
        return view('KepalaGudang.user.form', [
            'role'      => $role,
            'title'     => 'Tambah User'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required',
            'email'                 => 'required|email|unique:users,email',
            'role'                  => 'required',
            'password'              => 'required'
        ], [
            'name.required'         => 'Nama Wajib Diisi',
            'email.required'        => 'Email Wajib Diisi',
            'email.email'           => 'Format Email Harus Sesuai',
            'email.unique'          => 'Email Sudah Digunakan',
            'role.required'         => 'Hak Akses Wajib Diisi',
            'password.required'     => 'Password Wajib Diisi',
        ]);

        $role = Role::findOrFail($request->role);

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request['password']),
        ]);

        $user->assignRole($role);

        return redirect()->route('user.index')->with('success', 'Data Berhasil Ditambah');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user   = User::findOrFail($id);
        $role   = Role::all();

        return view('KepalaGudang.user.form', [
            'user'      => $user,
            'role'      => $role,
            'title'     => 'Edit user'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $request->validate([
            'name'              => 'required',
            'role'              => 'required',
            'email'             => 'required|email|unique:users,email,' . $id,
        ], [
            'name.required'         => 'Nama Wajib Diisi',
            'email.required'        => 'Email Wajib Diisi',
            'email.email'           => 'Format Email Harus Sesuai',
            'email.unique'          => 'Email Sudah Digunakan',
            'role.required'         => 'Hak Akses Wajib Diisi',
            'password.required'     => 'Password Wajib Diisi',
        ]);

        $role = Role::findOrFail($request->role);
        $user = User::findOrFail($id); // Assuming $id is the user ID

        $data = [
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request['password']),
        ];

        $user->update($data);
        $user->syncRoles($role);

        return redirect()->route('user.index')->with('success', 'Data Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        $user->delete();

        return response()->json(['status' => 'Data Telah Dihapus']);
    }
}
