<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use App\Models\Nasabah;
use Illuminate\Http\Request;


class PinjamanController extends Controller
{
    public function index()
    {
        $pinjaman = Pinjaman::with('anggota')->latest()->paginate(10);
        return view('pinjaman.index', compact('pinjaman'));
    }

    public function create()
    {
        $anggota = Nasabah::where('status', 'aktif')->get();
        return view('pinjaman.create', compact('anggota'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_anggota' => 'required',
            'jumlah_pinjaman' => 'required|numeric|min:50000',
            'bunga' => 'required|numeric|min:0',
            'lama_angsuran' => 'required|integer|min:1',
        ]);

        Pinjaman::create($request->all());

        return redirect()->route('pinjaman.index')->with('success', 'Pinjaman berhasil ditambahkan.');
    }

    public function edit(Pinjaman $pinjaman)
    {
        $anggota = Nasabah::where('status', 'aktif')->get();
        return view('pinjaman.edit', compact('pinjaman', 'anggota'));
    }

    public function update(Request $request, Pinjaman $pinjaman)
    {
        $request->validate([
            'id_anggota' => 'required',
            'jumlah_pinjaman' => 'required|numeric|min:50000',
            'bunga' => 'required|numeric|min:0',
            'lama_angsuran' => 'required|integer|min:1',
        ]);

        $pinjaman->update($request->all());

        return redirect()->route('pinjaman.index')->with('success', 'Pinjaman berhasil diperbarui.');
    }

    public function destroy(Pinjaman $pinjaman)
    {
        $pinjaman->delete();
        return redirect()->route('pinjaman.index')->with('success', 'Pinjaman berhasil dihapus.');
    }
}
