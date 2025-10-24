<?php

namespace App\Http\Controllers;


use App\Models\Simpanan;
use App\Models\Nasabah;
use Illuminate\Http\Request;

class SimpananController extends Controller
{
    public function index()
    {
        $simpanan = Simpanan::with('anggota')->latest()->paginate(10);
        return view('simpanan.index', compact('simpanan'));
    }

    public function create()
    {
        $anggota = Nasabah::where('status', 'aktif')->get();
        return view('simpanan.create', compact('anggota'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_anggota' => 'required',
            'jenis' => 'required',
            'jumlah' => 'required|numeric|min:1000',
        ]);

        Simpanan::create($request->all());

        return redirect()->route('simpanan.index')->with('success', 'Simpanan berhasil ditambahkan.');
    }

    public function edit(Simpanan $simpanan)
    {
        $anggota = Nasabah::where('status', 'aktif')->get();
        return view('simpanan.edit', compact('simpanan', 'anggota'));
    }

    public function update(Request $request, Simpanan $simpanan)
    {
        $request->validate([
            'id_anggota' => 'required',
            'jenis' => 'required',
            'jumlah' => 'required|numeric|min:1000',
        ]);

        $simpanan->update($request->all());

        return redirect()->route('simpanan.index')->with('success', 'Simpanan berhasil diperbarui.');
    }

    public function destroy(Simpanan $simpanan)
    {
        $simpanan->delete();
        return redirect()->route('simpanan.index')->with('success', 'Simpanan berhasil dihapus.');
    }
}
