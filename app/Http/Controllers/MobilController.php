<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use Illuminate\Http\Request;

class MobilController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $mobil = Mobil::where(function ($query) use ($search) {
            if ($search) {
                $query->where('merek', 'like', '%' . $search . '%')
                    ->orWhere('model', 'like', '%' . $search . '%');
                // ->orWhere('sub_mobil', 'like', '%' . $search . '%');
            }
        })->paginate(10);

        return view('mobil.index', compact('mobil'));
    }
    // public function getAllMobil()
    // {
    //     $mobil = Mobil::all();

    //     return view('mobil.index', ['mobil' => $mobil]);
    // }

    public function getMobilById($id)
    {
        $mobil = Mobil::find($id);

        if (!$mobil) {
            return response()->view('errors.404', [], 404);
        }

        return view('mobil.detail', ['mobil' => $mobil]);
    }

    public function create()
    {
        return view('mobil.create');
    }

    public function store(Request $request)
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

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'message' => 'Mobil berhasil dibuat',
                    'redirect' => route('mobil.index'),
                ], 200);
            } else {
                return redirect()->route('mobil.index')->with('success', 'Mobil berhasil dibuat');
            }
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Gagal membuat mobil: ' . $e->getMessage(),
                ], 200);
            } else {
                return redirect()->back()->withInput()->with('error', 'Gagal membuat mobil: ' . $e->getMessage());
            }
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $mobil = Mobil::where('id', $id)->first();

        return view('mobil.edit', compact('mobil'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'merek' => 'required',
            'model' => 'required',
            'nomor_plat' => 'required|unique:mobils,nomor_plat,' . $id,
            'tarif_sewa' => 'required|numeric|min:0',
        ]);

        $mobil = Mobil::find($id);

        if (!$mobil) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Mobil tidak ditemukan',
            ], 404);
        }

        try {
            $mobil->update([
                'merek' => $request->merek,
                'model' => $request->model,
                'nomor_plat' => $request->nomor_plat,
                'tarif_sewa' => $request->tarif_sewa,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'message' => 'Data mobil berhasil diperbarui',
                    'redirect' => route('mobil.index', ['id' => $id]),
                ], 200);
            } else {
                return redirect()->route('mobil.error', ['id' => $id])->with('success', 'Data mobil berhasil diperbarui');
            }
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Gagal memperbarui data mobil: ' . $e->getMessage(),
                ], 500);
            } else {
                return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data mobil: ' . $e->getMessage());
            }
        }
    }


    public function destroy($id)
    {
        $mobil = Mobil::find($id);

        if (!$mobil) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Mobil tidak ditemukan',
                ], 404);
            } else {
                return response()->view('errors.404', [], 404);
            }
        }

        try {
            $mobil->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'message' => 'Data mobil berhasil dihapus',
                ], 200);
            } else {
                return redirect()->route('mobil.index')->with('success', 'Data mobil berhasil dihapus');
            }
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Gagal menghapus data mobil: ' . $e->getMessage(),
                ], 500);
            } else {
                return redirect()->route('mobil.index')->with('error', 'Gagal menghapus data mobil: ' . $e->getMessage());
            }
        }
    }
}
