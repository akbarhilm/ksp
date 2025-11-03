<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Simpanan;
use App\Models\Pinjaman;
use App\Models\Angsuran;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
         // Total simpanan anggota
        //$totalSimpanan = Simpanan::sum('jumlah');

        // Total pinjaman yang masih berjalan
        $totalPinjaman = Pinjaman::where('status', 'berjalan')->sum('jumlah_pinjaman');

        // Total angsuran yang sudah dibayar
        $totalAngsuran = Angsuran::sum('jumlah_bayar');

        // Saldo kas (uang masuk - uang keluar)
        $totalDebit = Transaksi::sum('debit');
        $totalKredit = Transaksi::sum('kredit');
        $saldoKas = $totalDebit - $totalKredit;

        return view('dashboard.index', compact(
            'totalSimpanan',
            'totalPinjaman',
            'totalAngsuran',
            'saldoKas'
        ));
    }
}
