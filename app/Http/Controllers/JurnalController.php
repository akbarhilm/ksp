<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurnal;
use App\Models\Akun;

class JurnalController extends Controller
{
    // public function index(){

    //     return view('neraca.index');
    // }

    public function index(Request $request){

    $akunList = Akun::orderBy('nama_akun')->get();

    $filterAkun   = $request->id_akun;
    $tanggalAwal  = $request->tanggal_awal;
    $tanggalAkhir = $request->tanggal_akhir;

    $query = Jurnal::orderBy('tanggal_transaksi', 'asc');

    // Filter akun
    if ($filterAkun) {
        $query->where('id_akun', $filterAkun);
    }

    // Filter tanggal
    if ($tanggalAwal && $tanggalAkhir) {
        $query->whereBetween('tanggal_transaksi', [$tanggalAwal, $tanggalAkhir]);
    }

    $jurnal = $query->get();

    return view('jurnal.index', compact(
        'akunList',
        'jurnal',
        'filterAkun',
        'tanggalAwal',
        'tanggalAkhir'
    ));
}




public function bukuBesar(Request $request)
{
    $akunList = Akun::orderBy('nama_akun')->get();

    $filterAkun   = $request->id_akun;
    $tanggalAwal  = $request->tanggal_awal;
    $tanggalAkhir = $request->tanggal_akhir;

    // Query jurnal dasar
    $jurnalQuery = Jurnal::with('akun')->orderBy('tanggal_transaksi', 'asc');

    if ($filterAkun) {
        $jurnalQuery->where('id_akun', $filterAkun);
    }

    if ($tanggalAwal && $tanggalAkhir) {
        $jurnalQuery->whereBetween('tanggal_transaksi', [$tanggalAwal, $tanggalAkhir]);
    }

    $jurnalData = $jurnalQuery->get()->groupBy('id_akun');

    $bukuBesar = [];

    foreach ($jurnalData as $akunId => $items) {

        $akun = $akunList->firstWhere('id_akun', $akunId);
        if (!$akun) continue; // jika akun tidak ditemukan, skip

        // Hitung saldo awal
        $saldoAwal = 0;

        if ($tanggalAwal) {

            $saldoAwalRows = Jurnal::where('id_akun', $akunId)
                ->whereDate('tanggal_transaksi', '<', $tanggalAwal)
                ->get();

            foreach ($saldoAwalRows as $row) {
                // saldo tergantung tipe akun
                if (in_array($akun->tipe_akun, ['Kewajiban', 'Modal', 'Pendapatan'])) {
                    $saldoAwal += ($row->v_kredit - $row->v_debet);
                } else {
                    $saldoAwal += ($row->v_debet - $row->v_kredit);
                }
            }
        }

        // hitung saldo berjalan
        $saldo = $saldoAwal;

        foreach ($items as $row) {
            if (in_array($akun->tipe_akun, ['Kewajiban','Modal','Pendapatan'])) {
                $saldo += ($row->v_kredit - $row->v_debet);
            } else {
                $saldo += ($row->v_debet - $row->v_kredit);
            }

            $row->saldo = $saldo;
        }

        $bukuBesar[$akunId] = [
            'saldo_awal' => $tanggalAwal
                ? (object)[
                    'tanggal_transaksi' => $tanggalAwal,
                    'keterangan' => 'Saldo Awal',
                    'v_debet' => 0,
                    'v_kredit' => 0,
                    'saldo' => $saldoAwal
                ]
                : null,
            'data' => $items
        ];
    }

    return view('bukubesar.index', compact(
        'akunList',
        'bukuBesar',
        'filterAkun',
        'tanggalAwal',
        'tanggalAkhir'
    ));
}





 public function cari(Request $request){

        $jurnal = Jurnal::whereBetween('tanggal_transaksi', [$request->tanggal_awal, $request->tanggal_akhir])->get();

        return view('jurnal.index',compact('jurnal'));


}
}
