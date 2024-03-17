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

    public function index(Request $request)
    {
        return view('register');
    }

    // register
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required|unique:users',
            'no_sim' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4|regex:/[0-9]/', // Minimal 4 karakter termasuk angka
            'role' => 'nullable'
        ]);

        $user = User::create([
            'name' => $request->name,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'no_sim' => $request->no_sim,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Enkripsi password sebelum disimpan
            'role' => 'user',
        ]);

        return redirect()->route('login.index');
    }


    public function loginIndex()
    {

        return view('auth.login');
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
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'data' => null,
                        'message' => 'Login successfully.',
                        'redirect' => route('admin.dashboard'),
                    ], 200);
                } else {
                    return redirect()->route('welcome');
                }
            } elseif ($role === 'user') {
                $request->session()->regenerate();
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'data' => null,
                        'message' => 'Login successfully.',
                        'redirect' => route('user.index'),
                    ], 200);
                } else {
                    return redirect()->route('user.index');
                }
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Email or password is incorrect',
            ], 422);
        } else {
            return back()->with('error', 'Email atau kata sandi yang anda masukkan salah');
        }
    }


    public function logout()
    {

        Auth::logout();
        session()->forget('link');
        session()->flush();

        return redirect()->route('login.index');
    }
}
