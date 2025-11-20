<?php
namespace App\Http\Controllers;

use App\Models\Pinjaman;
use App\Models\Nasabah;
use Illuminate\Http\Request;

class PinjamanController extends Controller
{
  public function index(Request $request)
{
    // Mulai query
    $query = Pinjaman::query()->with('nasabah');

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
    if ($request->filled('status') 
        && in_array($request->status, ['aktif', 'lunas'])) 
    {
        $query->where('status', $request->status);
    }

    // Eksekusi query
    $pinjaman = $query->orderBy('created_at', 'desc')->get();
    return view('pinjaman.index', compact('pinjaman'));
}

}
