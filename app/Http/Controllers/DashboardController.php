<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Simpanan;
use App\Models\Pinjaman;
use App\Models\Angsuran;
use App\Models\Transaksi;
use App\Models\Jurnal;
use App\Models\Nasabah;

class DashboardController extends Controller
{
    public function index()
    {
     //============laba rugi bulanan ===================
    $tanggal  = now();
    // Query jurnal join akun
    $query = Jurnal::with('akun')
       ->whereMonth('tanggal_transaksi', $tanggal->month)
    ->whereYear('tanggal_transaksi', $tanggal->year);

    $jurnal = $query->get();

    // Hitung total pendapatan & beban
    $totalPendapatan = 0;
    $totalBeban      = 0;

    foreach ($jurnal as $row) {

        // Pendapatan → saldo normal kredit
        if ($row->akun->tipe_akun === 'Pendapatan') {
            $totalPendapatan += ($row->v_kredit - $row->v_debet);
        }

        // Beban → saldo normal debit
        if (in_array($row->akun->tipe_akun, ['Beban', 'Biaya'])) {
            $totalBeban += ($row->v_debet - $row->v_kredit);
        }
    }
    $laba = $totalPendapatan - $totalBeban;
//===============================================
//=================jumlah pinjaman===================
 $totalPinjaman = Pinjaman::whereMonth('created_at', $tanggal->month)
    ->whereYear('created_at', $tanggal->year)->sum('total_pinjaman');
//===============================================
//================jumlah nasabah======================
$totalnasabah = Nasabah::where('status','Aktif ')->count();
//===============================================
//================jumlah simpanan======================
$totalsimpanan = Simpanan::whereMonth('tanggal', $tanggal->month)
    ->whereYear('tanggal', $tanggal->year)->sum('v_kredit') - Simpanan::whereMonth('tanggal', $tanggal->month)
    ->whereYear('tanggal', $tanggal->year)->sum('v_debit');
//===============================================
//================List Pinjaman======================
$listpinjaman = Pinjaman::with('nasabah','pengajuan')->where('status','aktif')->get();
$lunas = Pinjaman::where('status','lunas')->whereMonth('updated_at', $tanggal->month)->whereYear('updated_at', $tanggal->year)->count();
//===============================================
   return view('dashboard.index', [
        'laba'             => $laba,
        'totalPinjaman'    => $totalPinjaman,
        'totalnasabah'     => $totalnasabah,
        'totalsimpanan'    => $totalsimpanan,
        'listpinjaman'     => $listpinjaman,
        'lunas'            => $lunas,
        
    ]);
}

}
