<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $user_count= User::get()->count();
        $mobil_count= Mobil::get()->count();
        $rental_count= Rental::get()->count();
        return view('admin.dashboard', compact('user_count', 'mobil_count', 'rental_count'));
    }
}
