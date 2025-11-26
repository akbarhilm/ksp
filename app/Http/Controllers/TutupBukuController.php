<?php
namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Bunga;
use App\Models\Simpanan;
use App\Models\Jurnal;
use App\Models\Rekening;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TutupBukuController extends Controller
{
    public function index(Request $request)
    {
        return view('tutupbuku.index');
    }

    public function store(Request $request)
     {
        DB::beginTransaction();

        try {

            $today = Carbon::parse($request->tanggal ?? now());

            // ============================
            // Ambil aturan bunga simpanan
            // ============================
            $bunga = Bunga::where('jenis_bunga', 'simpanan')->first();

            if (!$bunga) {
                return back()->with('error', 'Aturan bunga simpanan belum diatur.');
            }

            // ============================
            // Ambil semua rekening simpanan
            // ============================
            $rekening = Rekening::where('jenis_rekening','Tabungan')->get();

            foreach ($rekening as $r) {

                // ============================
                // HITUNG SALDO AKHIR
                // ============================
                $saldo = Simpanan::where('id_rekening', $r->id_rekening)
                        ->sum(DB::raw('v_kredit - v_debit'));

                // ============================
                // CEK THRESHOLD
                // ============================
                if ($saldo < $bunga->threshold) {
                    continue; // belum dapat bunga
                }

                // ============================
                // HITUNG BUNGA
                // ============================
                $bungaTabungan = $saldo * ($bunga->persentase / 100);

                if ($bungaTabungan <= 0) continue;

                // ============================
                // SIMPAN SIMPANAN (KREDIT)
                // ============================
                $Simpanan = Simpanan::create([
                    'id_rekening' => $r->id_rekening,
                    'tanggal' => $today,
                    'id_akun'=>0,
                    'jenis' => 'wajib',
                    'keterangan' => 'Bunga simpanan ' . $today->format('F Y'),
                    'v_debit' => 0,
                    'v_kredit' => $bungaTabungan,
                    'id_entry' => auth()->id()
                ]);

                // ============================
                // JURNAL AKUNTANSI (OPSIONAL)
                // ============================

                // Beban bunga
                Jurnal::create([
                    'tanggal_transaksi' => $today,
                    'id_akun' => '31', // Beban bunga
                    'v_debet' => $bungaTabungan,
                    'v_kredit' => 0,
                    'keterangan' => 'Beban bunga simpanan  ' . str_pad($r->id_nasabah,5,'0',STR_PAD_LEFT),
                    'id_entry' => auth()->id()
                ]);

               

                // Simpanan anggota
                Jurnal::create([
                    'tanggal_transaksi' => $today,
                    'id_akun' => '14',
                    'id_simpanan' => $Simpanan->id_simpanan,
                    'v_debet' => 0,
                    'v_kredit' => $bungaTabungan,
                    'keterangan' => 'Penambahan simpanan dari bunga '. str_pad($r->id_nasabah,5,'0',STR_PAD_LEFT),
                    'id_entry' => auth()->id()
                ]);
            }

            DB::commit();
            return back()->with('success', 'Bunga simpanan berhasil dibukukan.');

        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }
}

