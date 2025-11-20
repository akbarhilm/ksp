<?php

namespace App\Http\Controllers;

use App\Models\Bunga;
use Illuminate\Http\Request;

class BungaController extends Controller
{
    public function index()
    {
        $data = Bunga::orderBy('id_bunga','DESC')->paginate(10);
        return view('referensi.bunga.index', compact('data'));
    }

    public function create()
    {
        return view('referensi.bunga.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bunga' => 'required',
            'jenis_bunga' => 'required',
            'persentase' => 'required|numeric',
            'persentase2' => 'required|numeric',
            'threshold' => 'required|numeric',
            'threshold2' => 'required|numeric',
        ]);
        $request->request->add(['id_entry'=>auth()->id()]);
        Bunga::create($request->all());

        return redirect()->route('bunga.index')
            ->with('success', 'Data bunga berhasil ditambahkan.');
    }

    public function show(Bunga $bunga)
    {
        return view('referensi.bunga.show', compact('bunga'));
    }

    public function edit(Bunga $bunga)
    {
        return view('referensi.bunga.edit', compact('bunga'));
    }

    public function update(Request $request, Bunga $bunga)
    {
        $request->validate([
            'nama_bunga' => 'required',
            'persentase' => 'required|numeric',
        ]);

        $bunga->update($request->all());

        return redirect()->route('bunga.index')
            ->with('success', 'Data bunga berhasil diperbarui.');
    }

    public function destroy(Bunga $bunga)
    {
        $bunga->delete();
        return redirect()->route('bunga.index')
            ->with('success', 'Data bunga berhasil dihapus.');
    }
}
