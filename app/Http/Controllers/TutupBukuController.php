<?php
namespace App\Http\Controllers;

use App\Helpers\AssetHelper;
use App\Helpers\JurnalHelper;
use App\Models\Akun;
use App\Models\Bunga;
use App\Models\Simpanan;
use App\Models\Jurnal;
use App\Models\Rekening;
use App\Models\TutupBuku;
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
  $todayliteraly = Carbon::today();
            $lastDay = $todayliteraly->copy()->endOfMonth();

    //       if (!$todayliteraly->isSameDay($lastDay)) {
    //     return redirect()->back()->with('warning', 
    //         'Tutup buku hanya bisa dilakukan pada tanggal ' . $lastDay->format('d-m-Y')
    //     );
    // }
        DB::beginTransaction();

        try {
            $nojurnal = JurnalHelper::noJurnal();
$nilai = AssetHelper::susutGlobalTahunan(25,$nojurnal);
          
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
            $rekening = Rekening::with('nasabah')->where('jenis_rekening','Tabungan')->get();
             foreach ($rekening as $r) {

                // ============================
                // HITUNG SALDO AKHIR
                // ============================
                $saldo = Simpanan::where('id_rekening', $r->id_rekening)
                        ->sum(DB::raw('v_kredit - v_debit'));

                // ============================
                // CEK THRESHOLD
                // ============================
                   Jurnal::create([
                    'tanggal_transaksi' => now(),
                    'id_akun' => '49', // Beban bunga
                    'no_jurnal'=>$nojurnal,
                    'v_debet' =>0,
                    'v_kredit' => '2000',
                    'keterangan' => 'Pendapatan ADM dari Simpanan ' . str_pad($r->id_nasabah,5,'0',STR_PAD_LEFT).' / '.$r->nasabah[0]->nama,
                    'id_entry' => auth()->id()
                ]);
                $itu =  Jurnal::create([
                    'tanggal_transaksi' => now(),
                    'id_akun' => '36', // Beban bunga
                    'no_jurnal'=>$nojurnal,
                    'jenis'=>'simpanan',
                    'v_debet' =>'2000',
                    'v_kredit' => 0,
                    'keterangan' => 'Biaya ADM Simpanan ' . str_pad($r->id_nasabah,5,'0',STR_PAD_LEFT).' / '.$r->nasabah[0]->nama,
                    'id_entry' => auth()->id()
                ]);
                 $Simpanan = Simpanan::create([
                    'id_rekening' => $r->id_rekening,
                    'tanggal' => now(),
                    'id_akun'=>0,
                    'keterangan' => 'Biaya ADM ',
                    'v_debit' => '2000',
                    'v_kredit' => 0,
                    'id_jurnal'=>$itu->id_jurnal,
                    'no_jurnal'=>$nojurnal,
                    'id_entry' => auth()->id()
                ]);
                if ($saldo <= $bunga->threshold) {
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
                

                // ============================
                // JURNAL AKUNTANSI (OPSIONAL)
                // ============================

                // Beban bunga
                Jurnal::create([
                    'tanggal_transaksi' => now(),
                    'id_akun' => '72', // Beban bunga
                    'no_jurnal'=>$nojurnal,
                    'v_debet' => $bungaTabungan,
                    'v_kredit' => 0,
                    'keterangan' => 'Beban bunga simpanan  ' . str_pad($r->id_nasabah,5,'0',STR_PAD_LEFT).' / '.$r->nasabah[0]->nama,
                    'id_entry' => auth()->id()
                ]);
                //ADM
               

               

                // Simpanan anggota
                $ini = Jurnal::create([
                    'tanggal_transaksi' => now(),
                    'id_akun' => '36',
                    'no_jurnal'=>$nojurnal,
                    'jenis'=>'simpanan',
                    'v_debet' => 0,
                    'v_kredit' => $bungaTabungan,
                    'keterangan' => 'Penambahan simpanan dari bunga '. str_pad($r->id_nasabah,5,'0',STR_PAD_LEFT).' / '.$r->nasabah[0]->nama,
                    'id_entry' => auth()->id()
                ]);
            }
            $Simpanan = Simpanan::create([
                    'id_rekening' => $r->id_rekening,
                    'tanggal' => now(),
                    'id_akun'=>0,
                    'keterangan' => 'Bunga simpanan ',
                    'v_debit' => 0,
                    'v_kredit' => $bungaTabungan,
                    'no_jurnal'=>$nojurnal,
                    'id_jurnal'=>$ini->id_jurnal,
                    'id_entry' => auth()->id()
                ]);
               
            TutupBuku::create([
                'tanggal' => now()
            ]);

//             //shu
//             $akunSHU = Akun::where('nama_akun','LIKE','%SHU%')->first();
// $akunPend = Akun::where('tipe_akun','Pendapatan')->get();
// $akunBeban = Akun::where('tipe_akun','Beban')->get();

//             foreach($akunPend as $p){
//     $saldoPendapatan = Jurnal::where('id_akun',$p->id_akun)->sum(DB::raw('v_kredit - v_debet'));

//     if($saldoPendapatan > 0){
//         Jurnal::create([
//             'tanggal_transaksi' => '2025-11-30',
//             'id_akun' => $p->id_akun,
//             'no_jurnal'=>$nojurnal,
//             'v_debet' => $saldoPendapatan,
//             'v_kredit' => 0,
//             'keterangan' => 'Tutup Buku Pendapatan',
//              'id_entry' => auth()->id()
//         ]);

//         Jurnal::create([
//             'tanggal_transaksi' => '2025-11-30',
//             'id_akun' => $akunSHU->id_akun,
//             'no_jurnal'=>$nojurnal,
//             'v_debet' => 0,
//             'v_kredit' => $saldoPendapatan,
//             'keterangan' => 'SHU dari Pendapatan',
//              'id_entry' => auth()->id()
//         ]);
//     }
// }
//     foreach($akunBeban as $b){
//     $saldoBeban = Jurnal::where('id_akun',$b->id_akun)->sum(DB::raw('v_debet - v_kredit'));

//     if($saldoBeban > 0){
//         Jurnal::create([
//             'tanggal_transaksi' => '2025-11-30',
//             'id_akun' => $akunSHU->id_akun,
//             'no_jurnal'=>$nojurnal,
//             'v_debet' => $saldoBeban,
//             'v_kredit' => 0,
//             'keterangan' => 'SHU untuk Beban',
//              'id_entry' => auth()->id()
//         ]);

//         Jurnal::create([
//             'tanggal_transaksi' => '2025-11-30',
//             'id_akun' => $b->id_akun,
//             'no_jurnal'=>$nojurnal,
//             'v_debet' => 0,
//             'v_kredit' => $saldoBeban,
//             'keterangan' => 'Tutup Buku Beban',
//              'id_entry' => auth()->id()
//         ]);
//     }
// }


            DB::commit();
            return back()->with('success', 'Tutup Buku berhasil dibukukan.');

        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }
}

