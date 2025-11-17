<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurnal;
use App\Models\Akun;

class LabarugiController extends Controller
{
    // public function index(){

    //     return view('neraca.index');
    // }


      public function index(Request $request)
{
    $tanggalAwal  = $request->tanggal_awal;
    $tanggalAkhir = $request->tanggal_akhir;

    // Query jurnal join akun
    $query = Jurnal::with('akun');

    if ($tanggalAwal && $tanggalAkhir) {
        $query->whereBetween('tanggal_transaksi', [$tanggalAwal, $tanggalAkhir]);
    }

    $jurnal = $query->get();

    // Hitung total pendapatan & beban
    $totalPendapatan = 0;
    $totalBeban      = 0;

    foreach ($jurnal as $row) {

        // Pendapatan → saldo normal kredit
        if ($row->akun->tipe_akun === 'Pendapatan') {
            $totalPendapatan += ($row->v_kredit - $row->v_debet);
        }

        // Beban → saldo normal debit
        if (in_array($row->akun->tipe_akun, ['Beban', 'Biaya'])) {
            $totalBeban += ($row->v_debet - $row->v_kredit);
        }
    }

    $laba = $totalPendapatan - $totalBeban;

    return view('labarugi.index', [
        'tanggalAwal'      => $tanggalAwal,
        'tanggalAkhir'     => $tanggalAkhir,
        'totalPendapatan'  => $totalPendapatan,
        'totalBeban'       => $totalBeban,
        'laba'             => $laba,
    ]);
}

}
