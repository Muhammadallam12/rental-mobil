<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $id_pengguna = auth()->user()->id; // Ambil id_pengguna yang sedang login

        $rental = Rental::where('id_pengguna', $id_pengguna)->where(function ($query) use ($search) {
            if ($search) {
                $query->where('status', 'like', '%' . $search . '%')
                    ->orWhere('tanggal_mulai', 'like', '%' . $search . '%');
                // ->orWhere('sub_rental$rental', 'like', '%' . $search . '%');
            }
        })->paginate(10);


        return view('rental_user.index', compact('rental'));
    }
}
