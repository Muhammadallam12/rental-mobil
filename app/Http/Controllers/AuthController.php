<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //index

    //get all
    public function getAll()
    {
        $users = User::all();

        return $users;
    }

    //get user
    public function getRoleUser()
    {
        $users = User::where('role', 'user')->get();

        return $users;
    }

    // register
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'nomor_telepon' => 'required|unique:users',
            'nomor_SIM' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4|regex:/[0-9]/', // Minimal 4 karakter termasuk angka
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'nomor_telepon' => $request->nomor_telepon,
            'nomor_SIM' => $request->nomor_SIM,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Enkripsi password sebelum disimpan
            'role' => 'user',
        ]);

        return response()->json(['message' => 'Registrasi berhasil', 'user' => $user], 201);
    }


    //login
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            $role = auth()->user()->role;

            if ($role === 'admin') {
                $request->session()->regenerate();
                return redirect()->route('admin_dashboard');
            } elseif ($role === 'user') {
                $request->session()->regenerate();
                return redirect()->route('user');
            }
        }

        return back()->with('error', 'Email atau kata sandi yang anda masukkan salah');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
