<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurnal;
use App\Models\Akun;
use Yajra\DataTables\Facades\DataTables;


class JurnalController extends Controller
{
    // public function index(){

    //     return view('neraca.index');
    // }

    public function index(Request $request)
{
    $akunList = Akun::orderBy('nama_akun')->get();

    // AJAX request dari DataTables
    if ($request->ajax()) {

        $query = Jurnal::with('akun')
            ->select('tmjurnal.*')
            ->orderBy('id_jurnal', 'desc');

        // FILTER AKUN
        if ($request->id_akun) {
            $query->where('id_akun', $request->id_akun);
        }

        // FILTER TANGGAL
        if ($request->tanggal_awal && $request->tanggal_akhir) {
            $query->whereBetween('tanggal_transaksi', [
                $request->tanggal_awal,
                $request->tanggal_akhir
            ]);
        }

        return DataTables::of($query)

            ->addIndexColumn()
            ->addColumn('group_key', function($row){
        return md5($row->tanggal_transaksi . $row->keterangan);
    })
    ->rawColumns(['group_key'])

            ->addColumn('akun', function ($row) {
                return optional($row->akun)->nama_akun;
            })

            ->addColumn('debit', function ($row) {
                return number_format($row->v_debet, 0, ',', '.');
            })

            ->addColumn('kredit', function ($row) {
                return number_format($row->v_kredit, 0, ',', '.');
            })

            ->editColumn('tanggal_transaksi', function ($row) {
                return date('d-m-Y', strtotime($row->tanggal_transaksi));
            })

            ->rawColumns(['akun'])

            ->make(true);
    }
            return view('jurnal.index', compact('akunList'));

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

public function storeDouble(Request $request)
{
    $request->validate([
        'tanggal_transaksi' => 'required|date',
        'keterangan' => 'required',
        'akun_debet' => 'required|exists:trakun,id_akun',
        'akun_kredit' => 'required|exists:trakun,id_akun',
        'jumlah_debet' => 'required|numeric|min:1',
        'jumlah_kredit' => 'required|numeric|min:1',
    ]);

    // Validasi harus balance
    if ($request->jumlah_debet != $request->jumlah_kredit) {
        return back()->with('error', 'Debit dan Kredit harus sama nominalnya.');
    }

    // 1. INSERT DEBIT
    Jurnal::create([
        'tanggal_transaksi' => $request->tanggal_transaksi,
        'id_akun' => $request->akun_debet,
        'keterangan' => $request->keterangan,
        'v_debet' => $request->jumlah_debet,
        'v_kredit' => 0,
        'id_entry' => auth()->id() ?? 1,
    ]);

    // 2. INSERT KREDIT
    Jurnal::create([
        'tanggal_transaksi' => $request->tanggal_transaksi,
        'id_akun' => $request->akun_kredit,
        'keterangan' => $request->keterangan,
        'v_debet' => 0,
        'v_kredit' => $request->jumlah_kredit,
        'id_entry' => auth()->id() ?? 1,
    ]);

    return back()->with('success', 'Jurnal double entry berhasil disimpan.');
}




 public function cari(Request $request){

        $jurnal = Jurnal::whereBetween('tanggal_transaksi', [$request->tanggal_awal, $request->tanggal_akhir])->get();

        return view('jurnal.index',compact('jurnal'));


}
}
