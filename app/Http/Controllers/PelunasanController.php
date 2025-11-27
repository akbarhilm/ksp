<?php
namespace App\Http\Controllers;

use App\Models\Pinjaman;
use App\Models\Pengajuan;

use App\Models\Nasabah;
use Illuminate\Http\Request;

class PelunasanController extends Controller
{
  public function index(Request $request)
{
    // Mulai query
    $query = Pinjaman::query()->with('nasabah','pengajuan');

    // Filter by id_nasabah
    if ($request->filled('id_nasabah')) {
        $query->where('id_nasabah', $request->id_nasabah);
    }

    // Filter by nama nasabah
    if ($request->filled('nama')) {
        $query->whereHas('nasabah', function($q) use ($request) {
            $q->where('nama', 'like', '%'.$request->nama.'%');
        });
    }

    // Filter by status
   

    // Eksekusi query
     $query->where('status', 'aktif');
    $pinjaman = $query->orderBy('created_at', 'desc')->get();
    return view('pinjaman.pelunasan', compact('pinjaman'));
}

}
