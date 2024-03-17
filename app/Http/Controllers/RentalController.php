<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $rental = Rental::where(function ($query) use ($search) {
            if ($search) {
                $query->where('status', 'like', '%' . $search . '%')
                    ->orWhere('tanggal_mulai', 'like', '%' . $search . '%');
                // ->orWhere('sub_rental$rental', 'like', '%' . $search . '%');
            }
        })->paginate(10);

        return view('rental.index', compact('rental'));
    }

    public function getCompletedRentals()
    {
        $completedRentals = Rental::where('status', 'selesai')->get();

        return $completedRentals;
    }

    public function getOngoingRentals()
    {
        $ongoingRentals = Rental::where('status', 'dipinjam')->get();

        return $ongoingRentals;
    }

    public function getRentalById($id)
    {
        $rental = Rental::find($id);
        if (!$rental) {
            return redirect()->route('rental.index')->with('error', 'Rental not found');
        }
        return view('rental.show', ['rental' => $rental]);
    }

    public function create()
    {
        $mobil = Mobil::get();
        return view('rental.create', compact('mobil'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_mobil' => 'required|exists:mobils,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'total_harga' => 'required'
        ]);

        $id_pengguna = Auth::id(); // Mendapatkan ID pengguna yang sedang login

        // Cek ketersediaan mobil pada rentang tanggal penyewaan
        $existingRental = Rental::where('id_mobil', $request->id_mobil)
            ->where(function ($query) use ($request) {
                $query->whereBetween('tanggal_mulai', [$request->tanggal_mulai, $request->tanggal_selesai])
                    ->orWhereBetween('tanggal_selesai', [$request->tanggal_mulai, $request->tanggal_selesai]);
            })
            ->first();

        if ($existingRental) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Mobil tidak tersedia pada rentang tanggal yang dipilih',
                ], 422);
            } else {
                return redirect()->back()->withInput()->with('error', 'Mobil tidak tersedia pada rentang tanggal yang dipilih');
            }
        }

        // Buat entri rental
        $rental = Rental::create([
            'id_pengguna' => $id_pengguna, // Menggunakan ID pengguna yang sedang login
            'id_mobil' => $request->id_mobil,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => 'dipinjam', // Set status awal ke 'dipinjam'
            'total_harga' => $request->total_harga,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'Rental berhasil dibuat',
            ], 200);
        } else {
            return redirect()->route('rental.index')->with('success', 'Rental berhasil dibuat');
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $mobil = Mobil::get();
        $rental = Rental::where('id', $id)->first();

        return view('rental.edit', compact('rental', 'mobil'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_pengguna' => 'required|exists:users,id',
            'id_mobil' => 'required|exists:mobils,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $rental = Rental::find($id);
        if (!$rental) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Rental tidak ditemukan',
                ], 404);
            } else {
                return redirect()->route('rental.index')->with('error', 'Rental not found');
            }
        }

        // Cek ketersediaan mobil pada rentang tanggal penyewaan
        $existingRental = Rental::where('id_mobil', $request->id_mobil)
            ->where(function ($query) use ($request, $rental) {
                $query->whereBetween('tanggal_mulai', [$request->tanggal_mulai, $request->tanggal_selesai])
                    ->orWhereBetween('tanggal_selesai', [$request->tanggal_mulai, $request->tanggal_selesai]);
            })
            ->where('id', '!=', $id) // Exclude current rental
            ->first();

        if ($existingRental) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Mobil tidak tersedia pada rentang tanggal yang dipilih',
                ], 422);
            } else {
                return redirect()->back()->withInput()->with('error', 'Mobil tidak tersedia pada rentang tanggal yang dipilih');
            }
        }

        // Update data rental
        $rental->id_pengguna = $request->id_pengguna;
        $rental->id_mobil = $request->id_mobil;
        $rental->tanggal_mulai = $request->tanggal_mulai;
        $rental->tanggal_selesai = $request->tanggal_selesai;
        $rental->total_harga = $request->total_harga;
        $rental->save();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'Rental berhasil diperbarui',
            ], 200);
        } else {
            return redirect()->route('rental.index')->with('success', 'Rental berhasil diperbarui');
        }
    }

    public function destroy($id)
    {
        $rental = Rental::find($id);
        if (!$rental) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rental not found',
                ], 404);
            } else {
                return redirect()->route('rental.index')->with('error', 'Rental not found');
            }
        }

        // Hapus rental
        $rental->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Rental successfully deleted',
            ], 200);
        } else {
            return redirect()->route('rental.index')->with('success', 'Rental successfully deleted');
        }
    }


    //returnmobil
    public function returnCar(Request $request)
    {
        $request->validate([
            'nomor_plat' => 'required|exists:rentals,nomor_plat', // Memastikan nomor plat mobil yang dimasukkan ada dalam data rental
        ]);

        $nomor_plat = $request->nomor_plat;

        // Temukan data rental berdasarkan nomor plat mobil
        $rental = Rental::where('nomor_plat', $nomor_plat)->first();

        if (!$rental) {
            return redirect()->back()->with('error', 'Nomor plat mobil tidak ditemukan dalam data rental');
        }

        // Hitung jumlah hari sewa
        $tanggal_mulai = Carbon::parse($rental->tanggal_mulai);
        $tanggal_selesai = Carbon::parse($rental->tanggal_selesai);
        $jumlah_hari = $tanggal_mulai->diffInDays($tanggal_selesai);

        // Hitung total biaya sewa
        $total_biaya = $jumlah_hari * $rental->tarif_sewa;

        // Ubah status rental menjadi 'selesai'
        $rental->status = 'selesai';
        $rental->save();

        return redirect()->back()->with('success', 'Mobil dengan nomor plat ' . $nomor_plat . ' telah berhasil dikembalikan. Total biaya sewa: Rp ' . $total_biaya);
    }
}
