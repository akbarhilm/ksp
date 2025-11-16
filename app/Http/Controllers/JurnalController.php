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

    // Query awal untuk jurnal
    $jurnalQuery = Jurnal::orderBy('tanggal_transaksi', 'asc');

    // Filter akun
    if ($filterAkun) {
        $jurnalQuery->where('id_akun', $filterAkun);
    }

    // Hitung saldo awal
    $saldoAwalPerAkun = [];

    if ($tanggalAwal) {
        $saldoAwalQuery = Jurnal::select(
                'id_akun',
                DB::raw('SUM(v_debet - v_kredit) as saldo_awal')
            )
            ->whereDate('tanggal_transaksi', '<', $tanggalAwal);

        if ($filterAkun) {
            $saldoAwalQuery->where('id_akun', $filterAkun);
        }

        $saldoAwalQuery->groupBy('id_akun');

        foreach ($saldoAwalQuery->get() as $sa) {
            $saldoAwalPerAkun[$sa->id_akun] = $sa->saldo_awal;
        }
    }

    // Filter tanggal berjalan
    if ($tanggalAwal && $tanggalAkhir) {
        $jurnalQuery->whereBetween('tanggal_transaksi', [$tanggalAwal, $tanggalAkhir]);
    }

    $jurnalData = $jurnalQuery->get()->groupBy('id_akun');

    // Hitung saldo berjalan + tambah saldo awal di baris pertama
    $bukuBesar = [];

    foreach ($jurnalData as $akunId => $items) {

        $saldo = $saldoAwalPerAkun[$akunId] ?? 0;

        // Tambahkan baris saldo awal jika ada filter tanggal
        $awalRow = null;
        if ($tanggalAwal) {
            $awalRow = (object)[
                'tanggal_transaksi' => $tanggalAwal,
                'keterangan' => 'Saldo Awal',
                'v_debet' => 0,
                'v_kredit' => 0,
                'saldo' => $saldo
            ];
        }

        foreach ($items as $item) {
            $saldo += $item->v_debet - $item->v_kredit;
            $item->saldo = $saldo;
        }

        $bukuBesar[$akunId] = [
            'saldo_awal' => $awalRow,
            'data'       => $items
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
