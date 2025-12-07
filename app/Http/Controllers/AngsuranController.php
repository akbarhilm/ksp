<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pinjaman; // tmpinjaman
use App\Models\Angsuran;
use App\Models\Jurnal;
use App\Models\Pengajuan;
use App\Models\Rekening;
use App\Models\Simpanan;
use Illuminate\Support\Facades\DB;
use App\Helpers\JurnalHelper;

class AngsuranController extends Controller
{
    public function index($id_pinjaman)
{
    $pinjaman = Pinjaman::with('pengajuan','nasabah')->where('id_pinjaman',$id_pinjaman)->firstOrFail();
    $history = Angsuran::where('id_pinjaman', $id_pinjaman)
        ->orderBy('cicilan_ke', 'desc')
        ->get();

    return view('angsuran.index', compact('pinjaman', 'history'));
}

    public function store(Request $request, $id_pinjaman)
{
    $nojurnal = JurnalHelper::noJurnal();
    $pinjamanId = $id_pinjaman;
    $pokok = str_replace('.', '',$request->pokok);
    $bunga = str_replace('.', '',$request->bunga);
    $total =str_replace('.', '', $request->total_bayar);
    $idakunaset=0;
    $userId = auth()->id();

 $cicilanKe = Angsuran::where('id_pinjaman', $pinjamanId)->count() + 1;
    //bayar angsuran
     $angsuran = Angsuran::create([
        'id_pinjaman' => $pinjamanId,
        'bayar_pokok' => $pokok,
        'bayar_bunga' => $bunga,
        'total_bayar' => $total,
        'tanggal' => $request->tanggal,
        'cicilan_ke' => $cicilanKe,
        'id_entry' =>$userId,
        'metode' => $request->metode,
        'bayar_denda' =>str_replace('.', '',$request->denda),
        'simpanan' =>str_replace('.', '',$request->simpanan)
    ]);

    //update pinjaman
    $datapinjaman = Pinjaman::find($pinjamanId);
    $datapinjaman->update(['sisa_pokok'=>$datapinjaman->sisa_pokok - $pokok,'sisa_bunga'=>$datapinjaman->sisa_bunga - $bunga]);

    if($datapinjaman->sisa_pokok <=0 ){
        $datapinjaman->update(['status'=>'Lunas']);
        $pengajuan = Pengajuan::where('id_pengajuan',$datapinjaman->id_pengajuan)->get();
    if($pengajuan->asuransi >0){
      $dataasuransidebet = ['id_akun' => '82','no_jurnal'=>$nojurnal, 'id_pinjaman' => $datapinjaman->id_pinjaman, 'keterangan' => 'Dana Cadangan Klaim', 'v_debet' => $pengajuan->asuransi, 'v_kredit' => 0, 'id_entry' => auth()->user()->id];
            $dataasuransikredit = ['id_akun' => '50','no_jurnal'=>$nojurnal, 'id_pinjaman' => $datapinjaman->id_pinjaman, 'keterangan' => 'Pendapatan Asuransi ' . str_pad($datapinjaman->id_nasabah, 5, '0', STR_PAD_LEFT), 'v_debet' => 0, 'v_kredit' => $pengajuan->asuransi, 'id_entry' => auth()->user()->id];
            Jurnal::create($dataasuransidebet);
            Jurnal::create($dataasuransikredit);
    }
    }

    //add kesimpanan
    $rekening = Rekening::where('id_nasabah',$datapinjaman->id_nasabah)->where('jenis_rekening','Tabungan')->first();
    $simpanan = Simpanan::create([
        'id_rekening'=>$rekening->id_rekening,
        'id_akun' => 14,
        'tanggal' => $request->tanggal,
        'jenis'=>'pokok',
        'v_debit'=>0,
        'v_kredit'=>str_replace('.', '',$request->simpanan),
        'keterangan'=>'Simpanan dari angsuran',
        'id_entry'=>$userId


    ]);

    if($request->metode == 'Cash'){
        $idakunaset = 1;
    }else{
        $idakunaset = 5;
    }
   
    // 1. Debet Kas total (id_akun = 1)
    DB::table('tmjurnal')->insert([
        'id_akun' => $idakunaset, // Kas
        'id_pinjaman' => $pinjamanId,
        'no_jurnal'=>$nojurnal,
        'tanggal_transaksi' => now(),
        'keterangan' => 'Pembayaran angsuran pinjaman '.str_pad($datapinjaman->id_nasabah, 5, '0', STR_PAD_LEFT),
        'v_debet' => $total,
        'v_kredit' => 0,
        'id_entry' => $userId,
    ]);
    // angsuran pokok 
     DB::table('tmjurnal')->insert([
        'id_akun' => 9, // Kas
        'no_jurnal'=>$nojurnal,
        'id_pinjaman' => $pinjamanId,
        'tanggal_transaksi' => now(),
        'keterangan' => 'Piutang pinjaman '.str_pad($datapinjaman->id_nasabah, 5, '0', STR_PAD_LEFT),
        'v_debet' => 0,
        'v_kredit' => $pokok,
        'id_entry' => $userId,
    ]);

    // 3. Kredit Pendapatan Bunga (id_akun = 26)
    DB::table('tmjurnal')->insert([
        'id_akun' => 47, // Pendapatan Bunga Pinjaman
        'no_jurnal'=>$nojurnal,
        'id_pinjaman' => $pinjamanId,
        'tanggal_transaksi' => now(),
        'keterangan' => 'Pendapatan bunga pinjaman '.str_pad($datapinjaman->id_nasabah, 5, '0', STR_PAD_LEFT),
        'v_debet' => 0,
        'v_kredit' => $bunga,
        'id_entry' => $userId,
    ]);

 

    if($request->denda){
     DB::table('tmjurnal')->insert([
        'id_akun' => 80, // Pendapatan Denda
        'no_jurnal'=>$nojurnal,
        'id_pinjaman' => $pinjamanId,
        'tanggal_transaksi' => now(),
        'keterangan' => 'Pendapatan bunga pinjaman '.str_pad($datapinjaman->id_nasabah, 5, '0', STR_PAD_LEFT),
        'v_debet' => 0,
        'v_kredit' => str_replace('.', '',$request->denda),
        'id_entry' => $userId,
    ]);
    }

     // simpanan jurnal
    DB::table('tmjurnal')->insert([
        'id_akun' => 36, // Simpanan wajib Anggota
        'no_jurnal'=>$nojurnal,
        'id_simpanan' => $simpanan->id_simpanan,
        'tanggal_transaksi' => now(),
        'keterangan' => 'Simpanan wajib '.str_pad($datapinjaman->id_nasabah, 5, '0', STR_PAD_LEFT),
        'v_debet' => 0,
        'v_kredit' => str_replace('.', '',$request->simpanan),
        'id_entry' => $userId,
    ]);

   

    return redirect()->route('pinjaman.index')->with('success', 'Angsuran berhasil dicatat!');
}

public function pelunasan($id_pinjaman)

{
   
    $pinjaman = Pinjaman::with('pengajuan')->where('id_pinjaman',$id_pinjaman)->firstOrFail();
    $id_rekening = Rekening::where('id_nasabah', $pinjaman->id_nasabah)
        ->where('jenis_rekening', 'Tabungan')   
        ->value('id_rekening');
        $simpananpokok =  Simpanan::where('id_rekening', $id_rekening)->where('jenis','pokok')->sum('v_kredit') - Simpanan::where('id_rekening', $id_rekening)->where('jenis','pokok')->sum('v_debit');   
        $simpananwajib = Simpanan::where('id_rekening', $id_rekening)->where('jenis','wajib')->sum('v_kredit')  - Simpanan::where('id_rekening', $id_rekening)->where('jenis','wajib')->sum('v_debit');

    $history = Angsuran::where('id_pinjaman', $id_pinjaman)
        ->orderBy('tanggal', 'desc')
        ->get();

    return view('angsuran.pelunasan', compact('pinjaman', 'history', 'simpananpokok','simpananwajib'));

}

public function storePelunasan(Request $request, $id_pinjaman)
{
 DB::beginTransaction();
         try {
             $nojurnal = JurnalHelper::noJurnal();
    $pinjamanId = $id_pinjaman;
    $total =str_replace(',', '', $request->total_bayar);
    if($request->metode=="Cash"){
        $idakunaset=1;
    }else{
        $idakunaset=5;
    }
    $userId = auth()->id();

    //bayar pelunasan
     $angsuran = Angsuran::create([
        'id_pinjaman' => $pinjamanId,
        'bayar_pokok' => $total,
        'bayar_bunga' => 0,
        'total_bayar' => $total,
        'tanggal' => $request->tanggal,
        'cicilan_ke' => 0,
        'id_entry' =>$userId,
        'metode' => $request->metode,
        'bayar_denda' => 0,
        'simpanan' => 0
    ]);

    //update pinjaman
    $datapinjaman = Pinjaman::find($pinjamanId);
    $datapinjaman->update(['sisa_pokok'=>0,'sisa_bunga'=>0,'status'=>'Lunas']);
    $pengajuan = Pengajuan::where('id_pengajuan',$datapinjaman->id_pengajuan)->get();
    // 1. Debet Kas total (id_akun = 1)
    DB::table('tmjurnal')->insert([
        'id_akun' => $idakunaset, // Kas
        'no_jurnal'=>$nojurnal,
        'id_pinjaman' => $pinjamanId,
        'tanggal_transaksi' => now(),
        'keterangan' => 'Pelunasan pinjaman '.str_pad($datapinjaman->id_nasabah, 5, '0', STR_PAD_LEFT),
        'v_debet' => $total,
        'v_kredit' => 0,
        'id_entry' => $userId,
    ]);
    // angsuran pokok 
     DB::table('tmjurnal')->insert([
        'id_akun' => 9, // Kas
        'no_jurnal'=>$nojurnal,
        'id_pinjaman' => $pinjamanId,
        'tanggal_transaksi' => now(),
        'keterangan' => 'Piutang pinjaman '.str_pad($datapinjaman->id_nasabah, 5, '0', STR_PAD_LEFT),
        'v_debet' => 0,
        'v_kredit' => $total+$request->simpananpokok+$request->simpananwajib,
        'id_entry' => $userId,
    ]);

    // potong simpanan pokok dan wajib
    Simpanan::create([
        'id_rekening'=>Rekening::where('id_nasabah', $datapinjaman->id_nasabah)
        ->where('jenis_rekening', 'Tabungan')->value('id_rekening'),
        'id_akun' => 36, // Simpanan wajib Anggota
        'tanggal' => $request->tanggal,
        'jenis'=>'wajib',
        'v_debit'=>$request->simpananwajib,
        'v_kredit'=>0,
        'keterangan'=>'Penarikan untuk pelunasan',
        'id_entry'=>$userId]);
   Simpanan::create([
        'id_rekening'=>Rekening::where('id_nasabah', $datapinjaman->id_nasabah)
        ->where('jenis_rekening', 'Tabungan')->value('id_rekening'),
        'id_akun' => 35, // Simpanan Pokok Anggota
        'tanggal' => $request->tanggal,
        'jenis'=>'pokok',
        'v_debit'=>$request->simpananpokok,
        'v_kredit'=>0,
        'keterangan'=>'Penarikan untuk pelunasan',
        'id_entry'=>$userId]);
        
        DB::table('tmjurnal')->insert([
        'id_akun' => 36, // Simpanan wajib Anggota
        'no_jurnal'=>$nojurnal,
        'tanggal_transaksi' => now(),
        'keterangan' => 'Penarikan simpanan wajib '.str_pad($datapinjaman->id_nasabah, 5, '0', STR_PAD_LEFT),
        'v_debet' => $request->simpananwajib??0,
        'v_kredit' => 0,
        'id_entry' => $userId,
    ]);
        DB::table('tmjurnal')->insert([
        'id_akun' => 35, // Simpanan Pokok Anggota
        'no_jurnal'=>$nojurnal,
        'tanggal_transaksi' => now(),
        'keterangan' => 'Penarikan simpanan pokok '.str_pad($datapinjaman->id_nasabah, 5, '0', STR_PAD_LEFT),
        'v_debet' => $request->simpananpokok??0,
        'v_kredit' => 0,
        'id_entry' => $userId,
    ]);

    if($pengajuan->asuransi >0){
      $dataasuransidebet = ['id_akun' => '82','no_jurnal'=>$nojurnal, 'id_pinjaman' => $datapinjaman->id_pinjaman, 'keterangan' => 'Dana Cadangan Klaim', 'v_debet' => $pengajuan->asuransi, 'v_kredit' => 0, 'id_entry' => auth()->user()->id];
            $dataasuransikredit = ['id_akun' => '50','no_jurnal'=>$nojurnal, 'id_pinjaman' => $datapinjaman->id_pinjaman, 'keterangan' => 'Pendapatan Asuransi ' . str_pad($datapinjaman->id_nasabah, 5, '0', STR_PAD_LEFT), 'v_debet' => 0, 'v_kredit' => $pengajuan->asuransi, 'id_entry' => auth()->user()->id];
            Jurnal::create($dataasuransidebet);
            Jurnal::create($dataasuransikredit);
    }

   DB::commit();
   return redirect()->route('pelunasan.index')->with('success', 'Pelunasan berhasil dicatat!'); 
    } catch (\Exception $e) {
        DB::rollBack();
         dd($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
    }

    
}
 
}

