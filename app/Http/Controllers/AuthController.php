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
            'name' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required|unique:users',
            'no_sim' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4|regex:/[0-9]/', // Minimal 4 karakter termasuk angka
        ]);

        $user = User::create([
            'name' => $request->nama,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'no_sim' => $request->no_sim,
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
                return redirect()->route('welcome');
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
