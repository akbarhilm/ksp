<?php
namespace App\Services;

use Carbon\Carbon;
use DB;
use App\Models\{Bunga, Rekening, Simpanan, Jurnal, TutupBuku};
use App\Helpers\{JurnalHelper, AssetHelper};

class TutupBukuService
{
    public static function proses($tanggal, $userId = null)
    {
        DB::beginTransaction();

        try {
            $nojurnal = JurnalHelper::noJurnal();

            // Penyusutan aset (jika ada)
            AssetHelper::susutGlobalTahunan(25, $nojurnal);

            // ========================
            // Bunga simpanan
            // ========================
            $bunga = Bunga::where('jenis_bunga', 'simpanan')->first();
            if (!$bunga) {
                throw new \Exception('Aturan bunga simpanan belum diatur');
            }

            $rekening = Rekening::with('nasabah')
                ->where('jenis_rekening', 'Tabungan')
                ->get();

            foreach ($rekening as $r) {

                $saldo = Simpanan::where('id_rekening', $r->id_rekening)
                    ->sum(DB::raw('v_kredit - v_debit'));

                if ($saldo <= 0) continue;

                // ========================
                // ADMINISTRASI
                // ========================
                if ($saldo <= 10000) {
                    $adm = $saldo;
                } else {
                    $adm = 2000;
                }

                if ($adm > 0) {
                    // Pendapatan ADM
                    Jurnal::create([
                        'tanggal_transaksi' => $tanggal,
                        'id_akun' => 49,
                        'no_jurnal' => $nojurnal,
                        'v_debet' => 0,
                        'v_kredit' => $adm,
                        'keterangan' => 'Pendapatan ADM Simpanan '.str_pad($r->id_nasabah,5,'0',STR_PAD_LEFT).' / '.$r->nasabah[0]->nama,
                        'id_entry' =>  0,
                    ]);

                    // Simpanan berkurang
                    $j = Jurnal::create([
                        'tanggal_transaksi' => $tanggal,
                        'id_akun' => 36,
                        'no_jurnal' => $nojurnal,
                        'v_debet' => $adm,
                        'v_kredit' => 0,
                        'jenis' => 'simpanan',
                        'keterangan' => 'Biaya ADM Simpanan '.str_pad($r->id_nasabah,5,'0',STR_PAD_LEFT).' / '.$r->nasabah[0]->nama,
                        'id_entry' =>  0,
                    ]);

                    Simpanan::create([
                        'id_rekening' => $r->id_rekening,
                        'tanggal' => $tanggal,
                         'id_akun'=>0,
                        'v_debit' => $adm,
                        'v_kredit' => 0,
                        'id_jurnal' => $j->id_jurnal,
                        'no_jurnal' => $nojurnal,
                        'id_entry' =>  0,
                    ]);
                }

                // ========================
                // BUNGA SIMPANAN
                // ========================
                if ($saldo <= $bunga->threshold) continue;

                $nilaiBunga = $saldo * ($bunga->persentase / 100);
                if ($nilaiBunga <= 0) continue;

                // Beban bunga
                Jurnal::create([
                    'tanggal_transaksi' => $tanggal,
                    'id_akun' => 72,
                    'no_jurnal' => $nojurnal,
                    'v_debet' => $nilaiBunga,
                    'v_kredit' => 0,
                    'keterangan' => 'Beban bunga simpanan '.str_pad($r->id_nasabah,5,'0',STR_PAD_LEFT).' / '.$r->nasabah[0]->nama,
                    'id_entry' =>  0,
                ]);

                // Simpanan bertambah
                $j = Jurnal::create([
                    'tanggal_transaksi' => $tanggal,
                    'id_akun' => 36,
                    'no_jurnal' => $nojurnal,
                    'v_debet' => 0,
                    'v_kredit' => $nilaiBunga,
                    'jenis' => 'simpanan',
                    'keterangan' => 'Bunga simpanan '.str_pad($r->id_nasabah,5,'0',STR_PAD_LEFT).' / '.$r->nasabah[0]->nama,
                    'id_entry' =>  0,
                ]);

                Simpanan::create([
                    'id_rekening' => $r->id_rekening,
                    'tanggal' => $tanggal,
                     'id_akun'=>0,
                    'v_debit' => 0,
                    'v_kredit' => $nilaiBunga,
                    'id_jurnal' => $j->id_jurnal,
                    'no_jurnal' => $nojurnal,
                    'id_entry' =>  0,
                ]);
            }

            TutupBuku::create(['tanggal' => $tanggal]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
