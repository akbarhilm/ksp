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
public function edit($id){
    $angsuran = Angsuran::find($id);
    $pinjaman = Pinjaman::find($angsuran->id_pinjaman);
    return view('angsuran.edit',compact('angsuran','pinjaman'));
}
public function update(Request $request,$id){
        $request->merge([
            'total_bayar'=>str_replace('.','',$request->total_bayar),
            'bayar_pokok'=>str_replace('.','',$request->bayar_pokok),
            'bayar_bunga'=>str_replace('.','',$request->bayar_bunga),
            'bayar_denda'=>str_replace('.','',$request->bayar_denda),
            'simpanan'=>str_replace('.','',$request->simpanan)
        ]);
   $an =  Angsuran::find($id);
   $jur = Jurnal::where('no_jurnal',$an->no_jurnal)->get();
   $an->update($request->all());
   foreach($jur as $j){
    if($j->id_akun == '5'){
    Jurnal::where('id_jurnal',$j->id_jurnal)->update(['v_debet'=>$request->total_bayar,'tanggal_transaksi'=>$request->tanggal]);
    }
    if($j->id_akun == '9'){
        Jurnal::where('id_jurnal',$j->id_jurnal)->update(['v_kredit'=>$request->bayar_pokok, 'tanggal_transaksi'=>$request->tanggal]);
    }
    if($j->id_akun == '47'){
         Jurnal::where('id_jurnal',$j->id_jurnal)->update(['v_kredit'=>$request->bayar_bunga,'tanggal_transaksi'=>$request->tanggal]);
    }
    if($j->id_akun == '36'){
         Jurnal::where('id_jurnal',$j->id_jurnal)->update(['v_kredit'=>$request->simpanan,'tanggal_transaksi'=>$request->tanggal]);
    }
    if($j->id_akun == '80'){
         Jurnal::where('id_jurnal',$j->id_jurnal)->update(['v_kredit'=>$request->bayar_denda,'tanggal_transaksi'=>$request->tanggal]);
    }
   }
    return  redirect()->route('angsuran.index',$an->id_pinjaman)->with('success','Angsuran Berhasil Di Edit');
}

public function destroy($id){
    $angsuran = Angsuran::find($id);
    $jurnal = Jurnal::where('no_jurnal',$angsuran->no_jurnal)->delete();
    $angsuran->delete();
    return  redirect()->route('angsuran.index',$angsuran->id_pinjaman)->with('success','Angsuran Berhasil Di Hapus');
    
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
     

    //update pinjaman
    $datapinjaman = Pinjaman::find($pinjamanId);
    $datapinjaman->update(['sisa_pokok'=>$datapinjaman->sisa_pokok - $pokok,'sisa_bunga'=>$datapinjaman->sisa_bunga - $bunga]);

    if($datapinjaman->sisa_pokok <=0 ){
        $datapinjaman->update(['status'=>'Lunas']);
        $pengajuan = Pengajuan::where('id_pengajuan',$datapinjaman->id_pengajuan)->first();
    // if($pengajuan->asuransi >0){
    //   $dataasuransidebet = ['id_akun' => '82','no_jurnal'=>$nojurnal, 'id_pinjaman' => $datapinjaman->id_pinjaman, 'keterangan' => 'Dana Cadangan Klaim', 'v_debet' => $pengajuan->asuransi, 'v_kredit' => 0, 'id_entry' => auth()->user()->id];
    //         $dataasuransikredit = ['id_akun' => '50','no_jurnal'=>$nojurnal, 'id_pinjaman' => $datapinjaman->id_pinjaman, 'keterangan' => 'Pendapatan Asuransi ' . str_pad($datapinjaman->id_nasabah, 5, '0', STR_PAD_LEFT), 'v_debet' => 0, 'v_kredit' => $pengajuan->asuransi, 'id_entry' => auth()->user()->id];
    //         Jurnal::create($dataasuransidebet);
    //         Jurnal::create($dataasuransikredit);
    // }
     }

    //add kesimpanan
    

    if($request->metode == 'Cash'){
        $idakunaset = 1;
    }else{
        $idakunaset = 5;
    }
   
    // 1. Debet Kas total (id_akun = 1)
    DB::table('tmjurnal')->insert([
        'id_akun' => $idakunaset, // Kas
        'jenis'=>'angsuran',
        'no_jurnal'=>$nojurnal,
        'tanggal_transaksi' => $request->tanggal,
        'keterangan' => 'Pembayaran angsuran pinjaman '.str_pad($datapinjaman->id_nasabah, 5, '0', STR_PAD_LEFT),
        'v_debet' => $total,
        'v_kredit' => 0,
        'id_entry' => $userId,
    ]);
    // angsuran pokok 
     $ini = Jurnal::create([
        'id_akun' => 9, // Kas
        'no_jurnal'=>$nojurnal,
        'jenis'=>'angsuran',

        'tanggal_transaksi' => $request->tanggal,
        'keterangan' => 'Piutang pinjaman '.str_pad($datapinjaman->id_nasabah, 5, '0', STR_PAD_LEFT),
        'v_debet' => 0,
        'v_kredit' => $pokok,
        'id_entry' => $userId,
    ]);

    // 3. Kredit Pendapatan Bunga (id_akun = 26)
    DB::table('tmjurnal')->insert([
        'id_akun' => 47, // Pendapatan Bunga Pinjaman
        'no_jurnal'=>$nojurnal,
        'jenis'=>'angsuran',

        'tanggal_transaksi' => $request->tanggal,
        'keterangan' => 'Pendapatan bunga pinjaman '.str_pad($datapinjaman->id_nasabah, 5, '0', STR_PAD_LEFT),
        'v_debet' => 0,
        'v_kredit' => $bunga,
        'id_entry' => $userId,
    ]);

 

    if($request->denda){
     DB::table('tmjurnal')->insert([
        'id_akun' => 80, // Pendapatan Denda
        'no_jurnal'=>$nojurnal,
        'jenis'=>'angsuran',

        'tanggal_transaksi' => $request->tanggal,
        'keterangan' => 'Pendapatan bunga pinjaman '.str_pad($datapinjaman->id_nasabah, 5, '0', STR_PAD_LEFT),
        'v_debet' => 0,
        'v_kredit' => str_replace('.', '',$request->denda),
        'id_entry' => $userId,
    ]);
    }

     // simpanan jurnal
   $itu =  Jurnal::create([
        'id_akun' => 36, // Simpanan wajib Anggota
        'no_jurnal'=>$nojurnal,
        'jenis'=>'angsuran',

        'tanggal_transaksi' => $request->tanggal,
        'keterangan' => 'Simpanan wajib '.str_pad($datapinjaman->id_nasabah, 5, '0', STR_PAD_LEFT),
        'v_debet' => 0,
        'v_kredit' => str_replace('.', '',$request->simpanan),
        'id_entry' => $userId,
    ]);
$angsuran = Angsuran::create([
        'id_pinjaman' => $pinjamanId,
        'bayar_pokok' => $pokok,
        'bayar_bunga' => $bunga,
        'no_jurnal'=>$nojurnal,
        'id_jurnal'=>$ini->id_jurnal,
        'total_bayar' => $total-str_replace('.', '',$request->simpanan),
        'tanggal' => $request->tanggal,
        'cicilan_ke' => $cicilanKe,
        'id_entry' =>$userId,
        'metode' => $request->metode,
        'bayar_denda' =>str_replace('.', '',$request->denda),
        'simpanan' =>str_replace('.', '',$request->simpanan)
    ]);
   $rekening = Rekening::where('id_nasabah',$datapinjaman->id_nasabah)->where('jenis_rekening','Tabungan')->first();
    $simpanan = Simpanan::create([
        'id_rekening'=>$rekening->id_rekening,
        'id_akun' => 14,
        'no_jurnal'=>$nojurnal,
        'id_jurnal'=>$itu->id_jurnal,
        'tanggal' => $request->tanggal,
        'v_debit'=>0,
        'v_kredit'=>str_replace('.', '',$request->simpanan),
        'keterangan'=>'Simpanan dari angsuran '.str_pad($rekening->id_nasabah, 5, '0', STR_PAD_LEFT),
        'id_entry'=>$userId


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
        'tanggal_transaksi' => $request->tanggal,
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
        'tanggal_transaksi' => $request->tanggal,
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
        'tanggal_transaksi' => $request->tanggal,
        'keterangan' => 'Penarikan simpanan wajib '.str_pad($datapinjaman->id_nasabah, 5, '0', STR_PAD_LEFT),
        'v_debet' => $request->simpananwajib??0,
        'v_kredit' => 0,
        'id_entry' => $userId,
    ]);
        DB::table('tmjurnal')->insert([
        'id_akun' => 35, // Simpanan Pokok Anggota
        'no_jurnal'=>$nojurnal,
        'tanggal_transaksi' => $request->tanggal,
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

