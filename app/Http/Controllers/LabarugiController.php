<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurnal;
use App\Models\Akun;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class LabarugiController extends Controller
{
    // public function index(){

    //     return view('neraca.index');
    // }


      public function index(Request $request)
{
    $tanggalAwal  = $request->tanggal_awal;
$tanggalAkhir = $request->tanggal_akhir;

$query = Jurnal::with('akun');

if ($tanggalAwal && $tanggalAkhir) {
    $query->whereBetween('tanggal_transaksi', [$tanggalAwal, $tanggalAkhir]);
}

$jurnal = $query->get();

$totalPendapatan = 0;
$totalBeban      = 0;

$pendapatanPerAkun = [];
$bebanPerAkun = [];

foreach ($jurnal as $row) {

    $akun = $row->akun;
    if (!$akun) continue;

    // ✅ PENDAPATAN
    if ($akun->tipe_akun === 'Pendapatan') {

        $saldo = $row->v_kredit - $row->v_debet;
        $totalPendapatan += $saldo;

        if (!isset($pendapatanPerAkun[$akun->id_akun])) {
            $pendapatanPerAkun[$akun->id_akun] = [
                'kode' => $akun->kode_akun,
                'nama' => $akun->nama_akun,
                'total' => 0
            ];
        }

        $pendapatanPerAkun[$akun->id_akun]['total'] += $saldo;
    }

    // ✅ BEBAN / BIAYA
    if (in_array($akun->tipe_akun, ['Beban','Biaya'])) {

        $saldo = $row->v_debet - $row->v_kredit;
        $totalBeban += $saldo;

        if (!isset($bebanPerAkun[$akun->id_akun])) {
            $bebanPerAkun[$akun->id_akun] = [
                'kode' => $akun->kode_akun,
                'nama' => $akun->nama_akun,
                'total' => 0
            ];
        }

        $bebanPerAkun[$akun->id_akun]['total'] += $saldo;
    }
}

$laba = $totalPendapatan - $totalBeban;

return view('labarugi.index', [
    'tanggalAwal' => $tanggalAwal,
    'tanggalAkhir'=> $tanggalAkhir,
    'pendapatanPerAkun' => $pendapatanPerAkun,
    'bebanPerAkun'      => $bebanPerAkun,
    'totalPendapatan'  => $totalPendapatan,
    'totalBeban'       => $totalBeban,
    'laba'             => $laba,
]);

}


public function labaRugiPdf(Request $request)
{
   

    $tanggalAwal  = $request->tanggal_awal;
$tanggalAkhir = $request->tanggal_akhir;

$query = Jurnal::with('akun');

if ($tanggalAwal && $tanggalAkhir) {
    $query->whereBetween('tanggal_transaksi', [$tanggalAwal, $tanggalAkhir]);
}

$jurnal = $query->get();

$totalPendapatan = 0;
$totalBeban      = 0;

$pendapatanPerAkun = [];
$bebanPerAkun = [];

foreach ($jurnal as $row) {

    $akun = $row->akun;
    if (!$akun) continue;

    // ✅ PENDAPATAN
    if ($akun->tipe_akun === 'Pendapatan') {

        $saldo = $row->v_kredit - $row->v_debet;
        $totalPendapatan += $saldo;

        if (!isset($pendapatanPerAkun[$akun->id_akun])) {
            $pendapatanPerAkun[$akun->id_akun] = [
                'kode' => $akun->kode_akun,
                'nama' => $akun->nama_akun,
                'total' => 0
            ];
        }

        $pendapatanPerAkun[$akun->id_akun]['total'] += $saldo;
    }

    // ✅ BEBAN / BIAYA
    if (in_array($akun->tipe_akun, ['Beban','Biaya'])) {

        $saldo = $row->v_debet - $row->v_kredit;
        $totalBeban += $saldo;

        if (!isset($bebanPerAkun[$akun->id_akun])) {
            $bebanPerAkun[$akun->id_akun] = [
                'kode' => $akun->kode_akun,
                'nama' => $akun->nama_akun,
                'total' => 0
            ];
        }

        $bebanPerAkun[$akun->id_akun]['total'] += $saldo;
    }
}

$laba = $totalPendapatan - $totalBeban;

    $html = view('pdf.labarugi', compact(
        'tanggalAwal',
        'tanggalAkhir',
        'pendapatanPerAkun',
        'bebanPerAkun',
        'totalPendapatan',
        'totalBeban',
        'laba'
    ))->render();

    $pdf = PDF::loadHTML($html)
        ->setPaper('A4')
        ->setOrientation('portrait')
        ->setOption('margin-top', 15)
        ->setOption('margin-bottom', 15)
        ->setOption('margin-left', 15)
        ->setOption('margin-right', 15)
        ->setOption('encoding', 'utf-8');

    return $pdf->inline("LabaRugi-$tanggalAwal-$tanggalAkhir.pdf");
}


}
