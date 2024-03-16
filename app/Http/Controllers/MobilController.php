<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use Illuminate\Http\Request;

class MobilController extends Controller
{
    public function getAllMobil()
    {
        $mobil = Mobil::all();

        return view('mobil.index', ['mobil' => $mobil]);
    }

    public function getMobilById($id)
    {
        $mobil = Mobil::find($id);

        if (!$mobil) {
            return response()->view('errors.404', [], 404);
        }

        return view('mobil.detail', ['mobil' => $mobil]);
    }

    public function createMobil(Request $request)
    {
        $request->validate([
            'merek' => 'required',
            'model' => 'required',
            'nomor_plat' => 'required|unique:mobils',
            'tarif_sewa' => 'required|numeric|min:0',
        ]);

        try {
            $mobil = Mobil::create([
                'merek' => $request->merek,
                'model' => $request->model,
                'nomor_plat' => $request->nomor_plat,
                'tarif_sewa' => $request->tarif_sewa,
            ]);

            return redirect()->route('mobil.index')->with('success', 'Mobil berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat mobil: ' . $e->getMessage());
        }
    }


    public function updateMobil(Request $request, $id)
    {
        $request->validate([
            'merek' => 'required',
            'model' => 'required',
            'nomor_plat' => 'required|unique:mobils,nomor_plat,' . $id,
            'tarif_sewa' => 'required|numeric|min:0',
        ]);

        $mobil = Mobil::find($id);

        if (!$mobil) {
            return response()->view('errors.404', [], 404);
        }

        $mobil->update([
            'merek' => $request->merek,
            'model' => $request->model,
            'nomor_plat' => $request->nomor_plat,
            'tarif_sewa' => $request->tarif_sewa,
        ]);

        return redirect()->route('mobil.detail', ['id' => $id])->with('success', 'Data mobil berhasil diperbarui');
    }

    public function deleteMobil($id)
    {
        $mobil = Mobil::find($id);

        if (!$mobil) {
            return response()->view('errors.404', [], 404);
        }

        $mobil->delete();

        return redirect()->route('mobil.index')->with('success', 'Data mobil berhasil dihapus');
    }
}
