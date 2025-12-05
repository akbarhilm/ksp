<?php

namespace App\Http\Controllers;

use App\Helpers\JurnalHelper;
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
    $akunList = Akun::where('status','aktif')->orderBy('kode_akun')->get();

    // AJAX request dari DataTables
    if ($request->ajax()) {

        $query = Jurnal::with('akun')
            ->select('tmjurnal.*')
            ->orderBy('tanggal_transaksi', 'desc')
            ->orderBy('no_jurnal', 'desc');


        // FILTER AKUN
       

        // FILTER TANGGAL
        if ($request->tanggal_awal && $request->tanggal_akhir) {
            $query->whereBetween('tanggal_transaksi', [
                $request->tanggal_awal,
                $request->tanggal_akhir
            ]);
        }

        return DataTables::of($query)

            ->addIndexColumn()
            

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
             ->addColumn('no_jurnal', fn($row) => $row->no_jurnal ?? '-')

            ->rawColumns(['akun'])

            ->make(true);
    }
            return view('jurnal.index', compact('akunList'));

}




public function bukuBesar(Request $request)
{
    $akunList = Akun::where('status','aktif')->orderBy('kode_akun')->get();

    if ($request->ajax()) {

        $akunId = $request->id_akun;
        $tglAwal = $request->tanggal_awal;
        $tglAkhir = $request->tanggal_akhir;

        $query = Jurnal::with('akun')
            ->when($akunId, fn($q)=>$q->where('id_akun',$akunId))
            ->when($tglAwal && $tglAkhir, fn($q)=>$q->whereBetween('tanggal_transaksi',[$tglAwal,$tglAkhir]))
            ->orderBy('tanggal_transaksi','asc');

        $rows = $query->get();

        // Jika tidak pilih akun → hentikan
        if (!$akunId) {
            return DataTables::of(collect())->make(true);
        }

        $akun = Akun::find($akunId);
        $saldoAwal = 0;

        // ✅ SALDO AWAL
        if ($tglAwal) {
            $saldoAwalRows = Jurnal::where('id_akun',$akunId)
                ->whereDate('tanggal_transaksi','<',$tglAwal)
                ->get();

            foreach ($saldoAwalRows as $r) {
                if (in_array($akun->tipe_akun,['Kewajiban','Modal','Pendapatan'])) {
                    $saldoAwal += ($r->v_kredit - $r->v_debet);
                } else {
                    $saldoAwal += ($r->v_debet - $r->v_kredit);
                }
            }
        }

        // ✅ SALDO BERJALAN
        $saldo = $saldoAwal;
        $data = [];

        // TAMBAH BARIS SALDO AWAL
        if ($tglAwal) {
            $data[] = [
                'tanggal' => $tglAwal,
                'keterangan' => 'Saldo Awal',
                'debet' => 0,
                'kredit' => 0,
                'saldo' => $saldoAwal
            ];
        }

        // BARIS TRANSAKSI
         $totalDebet =0;
                $totalKredit =0;
                $saldo =0;
        foreach ($rows as $r) {

            if (in_array($akun->tipe_akun,['Kewajiban','Modal','Pendapatan'])) {
                $saldo += ($r->v_kredit - $r->v_debet);
                $totalDebet += $r->v_debet;
                $totalKredit += $r->v_kredit;
            } else {
                $saldo += ($r->v_debet - $r->v_kredit);
                 $totalDebet += $r->v_debet;
                $totalKredit += $r->v_kredit;
            }

            $data[] = [
                'tanggal' => $r->tanggal_transaksi,
                'keterangan' => $r->keterangan,
                'debet' => $r->v_debet,
                'kredit' => $r->v_kredit,
                'saldo' => $saldo,
                 'totalDebet' => $totalDebet,
            'totalKredit' => $totalKredit,
            ];
        }
        $saldoAkhir = $saldo; 
      
        

        return DataTables::of(collect($data))->with([
            'saldoAkhir'=>$saldoAkhir,
            'totalDebet'=>$totalDebet,
            'totalKredit'=>$totalKredit,
        ])
            ->make(true);
    }

    return view('bukubesar.index', compact('akunList'));
}

public function storeDouble(Request $request)
{
    $nojurnal = JurnalHelper::noJurnal();
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
        'no_jurnal'=>$nojurnal,
        'keterangan' => $request->keterangan,
        'v_debet' => $request->jumlah_debet,
        'v_kredit' => 0,
        'id_entry' => auth()->id() ?? 1,
    ]);

    // 2. INSERT KREDIT
    Jurnal::create([
        'tanggal_transaksi' => $request->tanggal_transaksi,
        'id_akun' => $request->akun_kredit,
        'no_jurnal'=>$nojurnal,
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
