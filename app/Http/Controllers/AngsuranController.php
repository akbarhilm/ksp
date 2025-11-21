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

class AngsuranController extends Controller
{
    public function index($id_pinjaman)
{
    $pinjaman = Pinjaman::with('pengajuan')->where('id_pinjaman',$id_pinjaman)->firstOrFail();
    $history = Angsuran::where('id_pinjaman', $id_pinjaman)
        ->orderBy('tanggal', 'desc')
        ->get();

    return view('angsuran.index', compact('pinjaman', 'history'));
}

    public function store(Request $request, $id_pinjaman)
{

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

    //add kesimpanan
    $rekening = Rekening::where('id_nasabah',$datapinjaman->id_nasabah)->where('jenis_rekening','Tabungan')->first();
    $simpanan = Simpanan::create([
        'id_rekening'=>$rekening->id_rekening,
        'id_akun' => 13,
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
        $idakunaset = 3;
    }
    // 1. Debet Kas (id_akun = 1)
    DB::table('tmjurnal')->insert([
        'id_akun' => $idakunaset, // Kas
        'id_pinjaman' => $pinjamanId,
        'tanggal_transaksi' => now(),
        'keterangan' => 'Pembayaran angsuran pinjaman '.str_pad($datapinjaman->id_nasabah, 5, '0', STR_PAD_LEFT),
        'v_debet' => $total,
        'v_kredit' => 0,
        'id_entry' => $userId,
    ]);

    // 3. Kredit Pendapatan Bunga (id_akun = 26)
    DB::table('tmjurnal')->insert([
        'id_akun' => 26, // Pendapatan Bunga Pinjaman
        'id_pinjaman' => $pinjamanId,
        'tanggal_transaksi' => now(),
        'keterangan' => 'Pendapatan bunga pinjaman '.str_pad($datapinjaman->id_nasabah, 5, '0', STR_PAD_LEFT),
        'v_debet' => 0,
        'v_kredit' => $bunga,
        'id_entry' => $userId,
    ]);

 

    if($request->denda){
     DB::table('tmjurnal')->insert([
        'id_akun' => 29, // Pendapatan Denda
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
        'id_akun' => 13, // Simpanan Pokok Anggota
        'id_simpanan' => $simpanan->id_simpanan,
        'tanggal_transaksi' => now(),
        'keterangan' => 'Simpanan pokok '.str_pad($datapinjaman->id_nasabah, 5, '0', STR_PAD_LEFT),
        'v_debet' => 0,
        'v_kredit' => str_replace('.', '',$request->simpanan),
        'id_entry' => $userId,
    ]);

    DB::table('tmjurnal')->insert([
        'id_akun' => 5, // Kas
        'id_pinjaman' => $pinjamanId,
        'tanggal_transaksi' => now(),
        'keterangan' => 'Piutang pinjaman '.str_pad($datapinjaman->id_nasabah, 5, '0', STR_PAD_LEFT),
        'v_debet' => 0,
        'v_kredit' => $pokok,
        'id_entry' => $userId,
    ]);

    return redirect()->route('pinjaman.index')->with('success', 'Angsuran berhasil dicatat!');
}

 
}
