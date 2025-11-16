<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pinjaman; // tmpinjaman
use App\Models\PembayaranAngsuran;
use App\Models\Jurnal;
use Illuminate\Support\Facades\DB;

class Angsuran extends Controller
{
    public function index($id_pinjaman)
{
    $pinjaman = Pinjaman::findOrFail($id_pinjaman);
    $history = PembayaranAngsuran::where('id_pinjaman', $id_pinjaman)
        ->orderBy('tanggal_bayar', 'asc')
        ->get();

    return view('angsuran.index', compact('pinjaman', 'history'));
}

    public function store(Request $request)
    {
        $request->validate([
            'id_pinjaman' => 'required|integer|exists:tmpinjaman,id_pinjaman',
            'pokok'       => 'required|numeric|min:0',
            'bunga'       => 'nullable|numeric|min:0',
            'id_akun_kas' => 'required|integer', // ID akun kas
            'tanggal'     => 'required|date',
        ]);

        DB::transaction(function() use ($request) {
            $pinjaman = Pinjaman::findOrFail($request->id_pinjaman);

            // 1. Simpan pembayaran angsuran
            PembayaranAngsuran::create([
                'id_pinjaman' => $pinjaman->id_pinjaman,
                'pokok_dibayar' => $request->pokok,
                'bunga_dibayar' => $request->bunga ?? 0,
                'tanggal_bayar' => $request->tanggal,
                'keterangan' => "Pembayaran angsuran #{$pinjaman->id_pinjaman}",
            ]);

            // 2. Update sisa pinjaman
            $pinjaman->sisa_pokok -= $request->pokok;
            $pinjaman->sisa_bunga -= $request->bunga ?? 0;

            // Update status jika lunas
            if($pinjaman->sisa_pokok <= 0 && $pinjaman->sisa_bunga <= 0){
                $pinjaman->status = 'lunas';
                $pinjaman->sisa_pokok = 0;
                $pinjaman->sisa_bunga = 0;
            }

            $pinjaman->save();

            // 3. Buat jurnal otomatis
            // 3a. Kas bertambah
            if($request->pokok > 0){
                Jurnal::create([
                    'id_akun' => $request->id_akun_kas,
                    'id_pinjaman' => $pinjaman->id_pinjaman,
                    'tanggal_transaksi' => $request->tanggal,
                    'keterangan' => "Pembayaran pokok pinjaman #{$pinjaman->id_pinjaman}",
                    'v_debet' => $request->pokok,
                    'v_kredit' => 0,
                ]);

                // Piutang Pinjaman berkurang
                Jurnal::create([
                    'id_akun' => $this->getAkunPiutangPinjaman(),
                    'id_pinjaman' => $pinjaman->id_pinjaman,
                    'tanggal_transaksi' => $request->tanggal,
                    'keterangan' => "Pelunasan pokok pinjaman #{$pinjaman->id_pinjaman}",
                    'v_debet' => 0,
                    'v_kredit' => $request->pokok,
                ]);
            }

            // 3b. Bunga
            if(($request->bunga ?? 0) > 0){
                // Kas bertambah
                Jurnal::create([
                    'id_akun' => $request->id_akun_kas,
                    'id_pinjaman' => $pinjaman->id_pinjaman,
                    'tanggal_transaksi' => $request->tanggal,
                    'keterangan' => "Pembayaran bunga pinjaman #{$pinjaman->id_pinjaman}",
                    'v_debet' => $request->bunga,
                    'v_kredit' => 0,
                ]);

                // Pendapatan bunga bertambah
                Jurnal::create([
                    'id_akun' => $this->getAkunPendapatanBunga(),
                    'id_pinjaman' => $pinjaman->id_pinjaman,
                    'tanggal_transaksi' => $request->tanggal,
                    'keterangan' => "Pendapatan bunga pinjaman #{$pinjaman->id_pinjaman}",
                    'v_debet' => 0,
                    'v_kredit' => $request->bunga,
                ]);
            }
        });

        return redirect()->back()->with('success', 'Pembayaran angsuran berhasil dicatat.');
    }

    // Fungsi helper ambil akun Piutang Pinjaman
    private function getAkunPiutangPinjaman()
    {
        // Ganti sesuai ID akun di database
        return 5; 
    }

    // Fungsi helper ambil akun Pendapatan Bunga
    private function getAkunPendapatanBunga()
    {
        // Ganti sesuai ID akun di database
        return 14; 
    }
}
