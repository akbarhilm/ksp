<?php

namespace App\Http\Controllers;

use App\Helpers\JurnalHelper;
use Illuminate\Http\Request;
use App\Models\Jurnal;
use App\Models\Akun;
use App\Models\Simpanan;
use App\Models\Angsuran;
use App\Models\Pinjaman;
use App\Models\Pengajuan;




use Egulias\EmailValidator\Result\Reason\DetailedReason;
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
            ->orderBy('tanggal_transaksi', 'desc');


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
            
            ->addColumn('nojurnal', function ($row) {
                $edit = route('jurnal.edit', $row->no_jurnal);
                return  '<a href="'.$edit.'">
                '.$row->no_jurnal.'
            </a>
        ';
            })

            ->addColumn('akun', function ($row) {
                return $row->akun->kode_akun.' / '.$row->akun->nama_akun;
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

            ->rawColumns(['akun','nojurnal'])

            ->make(true);
    }
            return view('jurnal.index', compact('akunList'));

}

public function edit($nojurnal){
    $jurnal = Jurnal::where('no_jurnal',$nojurnal)->get();
    $akun = Akun::where('status','aktif')->get();
    $angsuran='';
    if($jurnal[0]->jenis == 'pinjaman'){
        $pinjaman = Pinjaman::where('no_jurnal',$jurnal[0]->no_jurnal)->get();
        $angsuran = Angsuran::where('id_pinjaman',$pinjaman[0]->id_pinjaman)->count() > 0 ? true : false;
        
    }
    // $data =['no_jurnal'=>$jurnal[0]->no_jurnal,'tanggal_transaksi'=>$jurnal[0]->tanggal_transaksi,'keterangan'=>$jurnal[0]->keterangan];
    // foreach($jurnal as $j){
    //     $data['detail'][]=['id_jurnal'=>$j->id_jurnal,'id_akun'=>$j->id_akun,'debit'=>$j->v_debet,'kredit'=>$j->v_kredit,'jenis'=>$j->jenis];
    // }
    return view('jurnal.edit',compact('jurnal','akun','angsuran'));
}

public function update(Request $request){
    //  if ($request->jumlah_debet != $request->jumlah_kredit) {
    //     return back()->with('error', 'Debit dan Kredit harus sama nominalnya.');
    // }
   $tgl =  $request->tanggal_transaksi;
   $ket = $request->keterangan;
   foreach($request->id_jurnal as $i=>$jr){
   $detail['detail'][]=['id_akun'=>$request->akun_id[$i],'id_jurnal'=>$request->id_jurnal[$i],'v_debet'=>$request->v_debet[$i],'v_kredit'=>$request->v_kredit[$i],'jenis'=>$request->jenis[$i]];
   $detail['detail'][]=['id_akun'=>$request->akun_id[$i],'id_jurnal'=>$request->id_jurnal[$i],'v_debet'=>$request->v_debet[$i],'v_kredit'=>$request->v_kredit[$i],'jenis'=>$request->jenis[$i]];
   }
   foreach($detail['detail'] as $i=>$d){
        $d['keterangan'] = $ket;
        $d['tanggal_transaksi'] = $tgl;
       
      $jurnal = Jurnal::find($d['id_jurnal'])->update($d);
     
       if($d['jenis']=='simpanan'){
            $s = ['v_debit'=>$d['v_debet'],'v_kredit'=>$d['v_kredit'],'id_akun'=>$d['id_akun'],'keterangan'=>$ket,'tanggal'=>$tgl];

             Simpanan::where('id_jurnal',$d['id_jurnal'])->update($s);
        }
        if($d['jenis']=='angsuran'){
            $j = Jurnal::find($d['id_jurnal']);
            $angsuran = Angsuran::where('no_jurnal',$j->no_jurnal);
            if($d['id_akun']=='9'){
                $angsuran->update(['bayar_pokok'=>$d['v_kredit']]);
            }
            if($d['id_akun']=='47'){
                 $angsuran->update(['bayar_bunga'=>$d['v_kredit']]);
            }
            if($d['id_akun']=='80'){
                $angsuran->update(['bayar_denda'=>$d['v_kredit']]);
            }
            if($d['id_akun']=='36'){

             Simpanan::where('id_jurnal',$d['id_jurnal'])->update(['v_kredit'=>$d['v_kredit']]);
                
                $angsuran->update(['simpanan'=>$d['v_kredit']]);
            }
        }
        if($d['jenis']=='pinjaman'){
            $j = Jurnal::find($d['id_jurnal']);

            $pinjaman = Pinjaman::where('no_jurnal',$j->no_jurnal)->first();
            $pengajuan = Pengajuan::find($pinjaman->id_pengajuan);
            if($d['id_akun']=='9'){
                $pokok = ($pengajuan->bunga * $pengajuan->tenor * $d['v_debet'])/100;
                $pengajuan->update(['jumlah_pencairan'=>$d['v_debet']]);
                $pinjaman->update(['total_pinjaman'=>$d['v_debet'],'sisa_pokok'=>$d['v_debet'],'sisa_bunga'=>$pokok]);
            }
        }
       
      
    }
  return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil diperbarui.');
}

public function hapus($nojurnal){
    $jurnal = Jurnal::where('no_jurnal',$nojurnal)->get();
    foreach($jurnal as $j){
        if($j->jenis == 'simpanan'){
            $simpanan = simpanan::where('id_jurnal',$j->id_jurnal)->first();
            $simpanan->delete();
        }
        if($j->jenis == 'angsuran'){
            $angsuran = Angsuran::where('no_jurnal',$nojurnal)->first();
            $angsuran->delete();
            $s = simpanan::where('id_jurnal',$j->id_jurnal)->first();
            $s->delete();
        }
        $j->delete();
    }
    return back()->with('success', 'Jurnal berhasil dihapus.');
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



public function destroy($nojurnal){
     $jurnal = Jurnal::where('no_jurnal',$nojurnal)->get();
    foreach($jurnal as $j){
        if($j->jenis == 'simpanan'){
            $simpanan = simpanan::where('id_jurnal',$j->id_jurnal)->delete();
            
        }
        if($j->jenis == 'angsuran'){
            $angsuran = Angsuran::where('id_jurnal',$j->id_jurnal)->delete();
        }
         $j->delete();
        }

        if($j->jenis == 'pinjaman'){
            $pinjaman = Pinjaman::where('no_jurnal',$nojurnal)->first();
            $pengajuan =  Pengajuan::where('id_pengajuan',$pinjaman->id_pengajuan)->update(['tanggal_pencairan'=>null,'status'=>'approv']);
            $pl = Pinjaman::where('id_nasabah', $pinjaman->id_nasabah)->where('status', 'lunas')->first();
           $pinjaman->delete() ;
                 
             $pl->update(['status'=>'aktif']);
        }
       
    
  return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil dihapus.');

    

}

 public function cari(Request $request){

        $jurnal = Jurnal::whereBetween('tanggal_transaksi', [$request->tanggal_awal, $request->tanggal_akhir])->get();

        return view('jurnal.index',compact('jurnal'));


}
}
