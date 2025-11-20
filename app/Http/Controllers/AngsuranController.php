<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pinjaman; // tmpinjaman
use App\Models\Angsuran;
use App\Models\Jurnal;
use App\Models\Pengajuan;
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
    $pokok = $request->pokok;
    $bunga = $request->bunga;
    $total = $request->total_bayar;

    $userId = auth()->id();

 $cicilanKe = Angsuran::where('id_pinjaman', $pinjamanId)->count() + 1;

     $angsuran = Angsuran::create([
        'id_pinjaman' => $pinjamanId,
        'bayar_pokok' => $pokok,
        'bayar_bunga' => $bunga,
        'total_bayar' => $total,
        'tanggal' => $request->tanggal,
        'cicilan_ke' => $cicilanKe,
        'id_entry' =>$userId
        //'id_akun_kas' => $request->id_akun_kas
    ]);
    $datapinjaman = Pinjaman::find($pinjamanId);
    $datapinjaman->update(['sisa_pokok'=>$datapinjaman->sisa_pokok - $pokok,'sisa_bunga'=>$datapinjaman->sisa_bunga - $bunga]);

    // 1. Debet Kas (id_akun = 1)
    DB::table('tmjurnal')->insert([
        'id_akun' => 1, // Kas
        'id_pinjaman' => $pinjamanId,
        'tanggal_transaksi' => now(),
        'keterangan' => 'Pembayaran angsuran pinjaman '.str_pad($datapinjaman->id_nasabah, 5, '0', STR_PAD_LEFT),
        'v_debet' => $total,
        'v_kredit' => 0,
        'id_entry' => $userId,
    ]);

    // 2. Kredit Piutang Pokok (id_akun = 5)
    DB::table('tmjurnal')->insert([
        'id_akun' => 5, // Piutang Pinjaman Anggota
        'id_pinjaman' => $pinjamanId,
        'tanggal_transaksi' => now(),
        'keterangan' => 'Pengurangan pokok pinjaman '.str_pad($datapinjaman->id_nasabah, 5, '0', STR_PAD_LEFT),
        'v_debet' => 0,
        'v_kredit' => $pokok,
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

    return redirect()->route('pinjaman.index')->with('success', 'Angsuran berhasil dicatat!');
}

 
}
