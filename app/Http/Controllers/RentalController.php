<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    public function getAllRentals()
    {
        $rentals = Rental::all();
        return view('rental.index', ['rentals' => $rentals]);
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

    public function createRental(Request $request)
    {
        $request->validate([
            'id_pengguna' => 'required|exists:users,id',
            'id_mobil' => 'required|exists:mobils,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        // Cek ketersediaan mobil pada rentang tanggal penyewaan
        $existingRental = Rental::where('id_mobil', $request->id_mobil)
            ->where(function ($query) use ($request) {
                $query->whereBetween('tanggal_mulai', [$request->tanggal_mulai, $request->tanggal_selesai])
                    ->orWhereBetween('tanggal_selesai', [$request->tanggal_mulai, $request->tanggal_selesai]);
            })
            ->first();

        if ($existingRental) {
            return redirect()->back()->withInput()->with('error', 'Mobil tidak tersedia pada rentang tanggal yang dipilih');
        }

        // Buat entri rental
        $rental = Rental::create([
            'id_pengguna' => $request->id_pengguna,
            'id_mobil' => $request->id_mobil,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => 'dipinjam', // Set status awal ke 'dipinjam'
        ]);

        return redirect()->route('rental.create')->with('success', 'Rental berhasil dibuat');
    }

    public function updateRental(Request $request, $id)
    {
        $request->validate([
            'id_pengguna' => 'required|exists:users,id',
            'id_mobil' => 'required|exists:mobils,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $rental = Rental::find($id);
        if (!$rental) {
            return redirect()->route('rental.index')->with('error', 'Rental not found');
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
            return redirect()->back()->withInput()->with('error', 'Mobil tidak tersedia pada rentang tanggal yang dipilih');
        }

        // Update data rental
        $rental->id_pengguna = $request->id_pengguna;
        $rental->id_mobil = $request->id_mobil;
        $rental->tanggal_mulai = $request->tanggal_mulai;
        $rental->tanggal_selesai = $request->tanggal_selesai;
        $rental->save();

        return redirect()->route('rental.index')->with('success', 'Rental berhasil diperbarui');
    }

    public function deleteRental($id)
    {
        $rental = Rental::find($id);
        if (!$rental) {
            return redirect()->route('rental.index')->with('error', 'Rental not found');
        }

        // Hapus rental
        $rental->delete();

        return redirect()->route('rental.index')->with('success', 'Rental berhasil dihapus');
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
