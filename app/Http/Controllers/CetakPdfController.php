<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use App\Models\Pengajuan;
use App\Models\PengajuanJaminan;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Jurnal;

use App\Models\Akun;

use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Mpdf\Mpdf;
use App\Helpers\PinjamanHelper;

use Illuminate\Http\Request;

class CetakPdfController extends Controller
{

      public function nplPerResort(Request $request)
    {
        $tanggal = $request->tanggal ?? date('Y-m-d');

        $pinjaman = Pinjaman::with('nasabah')
            ->where('status', 'aktif')
            ->get();

        $dataResort = [];

        foreach ($pinjaman as $p) {

            if (!$p->nasabah) continue;

            $user = User::where('kode_resort',$p->nasabah->kode_resort)->first() ?? 'UNKNOWN';
            if($user == 'UNKNOWN'){
               $resort=$user;
            }else{
               $resort = $user->kode_resort.' - '.$user->nama;
            }
           
            // hitung hari tunggakan & kolek
            $data = PinjamanHelper::hitungDenda($p->id_pinjaman);
            // $kolek     = Helper::getKolektibilitas($hariTelat);

            $sisa = $p->sisa_pokok;

            if (!isset($dataResort[$resort])) {
                $dataResort[$resort] = [
                    'total_kredit' => 0,
                    'total_npl'    => 0,
                    'detail'       => [],
                ];
            }

            $dataResort[$resort]['total_kredit'] += $sisa;

            // NPL = C3, C4, C5
            if (in_array($data['kolek'], ['C3','C4','C5'])) {
                $dataResort[$resort]['total_npl'] += $sisa;
            }
 $last = Carbon::parse($data['tgl_cair'])
            ->addMonths($data['cicilan_lunas']);
            $dataResort[$resort]['detail'][] = [
                'nasabah' => str_pad($p->nasabah->id_nasabah,5,'0',STR_PAD_LEFT).' / '.$p->nasabah->nama,
                'tgl_cair' => $data['tgl_cair'],
                'cicilan_terakhir'=>$last,
                'sisa'    => $sisa,
                'hari'    => $data['haritelat'],
                'kolek'   => $data['kolek'],
            ];
        }

        // hitung rasio per resort
        foreach ($dataResort as $resort => $row) {
            $dataResort[$resort]['rasio'] = $row['total_kredit'] > 0
                ? round(($row['total_npl'] / $row['total_kredit']) * 100, 2)
                : 0;
        }

        return view('npl.resumeresort', compact('tanggal', 'dataResort'));
    }
    
public function cetakJaminan($id)
{
    // $pinjaman = Pinjaman::with('nasabah')->where('id_pinjaman',$id)->first();
    $user = User::where('jabatan','Pimpinan')->first();
    $jaminan = PengajuanJaminan::with('pengajuan.rekening.nasabah')->where('id_pengajuan',$id)->get();
    $data =['nama'=>$jaminan[0]->pengajuan->rekening[0]->nasabah[0]->nama,'alamat'=>$jaminan[0]->pengajuan->rekening[0]->nasabah[0]->alamat,'jaminan'=>[]];
    foreach($jaminan as $j){
        if($j->jenis_jaminan==$j->keterangan){
           if(str_contains(strtolower($j->jenis_jaminan),'pin') || str_contains(strtolower($j->ket),'pin')){
            
           }else{
            $jj[] =  ['jenis'=>'raw','ket'=>$j->keterangan];
           }
        }else{
             if(str_contains(strtolower($j->jenis_jaminan),'pin') || str_contains(strtolower($j->ket),'pin')){
            
           }else{
           $jj[] =  ['jenis'=>$j->jenis_jaminan,'ket'=>$j->keterangan];
           }
            
        }
    }
    $data['jaminan']=$jj;
    $data['ttd']=$user->nama;

   // return view('pdf.sttjaminan', compact('data'));
   $html = view('pdf.sttjaminan', compact('data'))->render();

    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A5-P', // LANDSCAPE
        'margin_top' => 10,
        'margin_bottom' => 10,
        'margin_left' => 10,
        'margin_right' => 10,
        'default_font' => 'dejavusans'
    ]);

    $mpdf->WriteHTML($html);

    return response($mpdf->Output(
        'Riwayat-Pembayaran.pdf',
        'I'
    ))->header('Content-Type', 'application/pdf');
}

public function cetakPencairan($id){
    $pinjaman = Pinjaman::with('nasabah','pengajuan')->where('id_pinjaman',$id)->first();
    if($pinjaman->ref != null){
    $pinjamanlama = Pinjaman::where('id_pinjaman',$pinjaman->ref)->first();
    $jumlahlama = $pinjamanlama->sisa_pokok + $pinjamanlama->sisa_bunga;
    }
    $materai = $pinjaman->pengajuan->materai;
        $provisi = $pinjaman->pengajuan->admin;
        $survey = $pinjaman->pengajuan->survey;
        $asuransi = $pinjaman->pengajuan->asuransi;
        $simpanan_pokok = $pinjaman->pengajuan->simpanan_pokok;





    $data = [
        'no_anggota' => str_pad($pinjaman->id_nasabah,5,'0',STR_PAD_LEFT),
        'nama' => $pinjaman->nasabah->nama,
        'telepon' => $pinjaman->nasabah->no_telp,
        'tgl_lahir' => date('d-m-Y', strtotime($pinjaman->nasabah->tgl_lahir)),
        'jumlah_pinjaman' => $pinjaman->total_pinjaman,
        'provisi' => $pinjaman->pengajuan->admin,
        'materai' => $pinjaman->pengajuan->materai,
        'survey' => $pinjaman->pengajuan->survey,
        'asuransi' => $pinjaman->pengajuan->asuransi,
        'tgl_cair'=>$pinjaman->pengajuan->tanggal_pencairan,
        'tenor'=>$pinjaman->pengajuan->tenor,
        'bunga'=>$pinjaman->pengajuan->bunga,
        'id'=>$pinjaman->id_pinjaman,
        'simpanan_pokok' => $pinjaman->pengajuan->simpanan_pokok,
        'pinjamanlama'=>$jumlahlama??0,
        'diterima_bersih' => ($pinjaman->total_pinjaman -$provisi-$survey-$asuransi-$materai - $simpanan_pokok-($jumlahlama??0)),
    ];
   // return view('pdf.pencairan',compact('data'));
   $html = view('pdf.pencairan', compact('data'))->render();

    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A5-P', 
        // LANDSCAPE
        'margin_top' => 10,
        'margin_bottom' => 10,
        'margin_left' => 20,
        'margin_right' => 10,
        'default_font' => 'dejavusans'
    ]);

    $mpdf->WriteHTML($html);

    return response($mpdf->Output(
        'SPK.pdf',
        'I'
    ))->header('Content-Type', 'application/pdf');
    // $pdf = PDF::loadView('pdf.pencairan', compact('data'))
    //     ->setPaper('A5', 'landscape')
    //     ->setOption('enable-local-file-access', true)
    //     ->setOption('encoding','utf-8');

    // return $pdf->inline('Pencairan-'.$pinjaman->nasabah->nama.'.pdf');
}

public function cetakRiwayat($id)
{
    $pinjaman = Pinjaman::with('angsuran','nasabah','pengajuan')->find($id);

    // susun data angsuran sesuai format blade di atas
    $angsuran = $pinjaman->angsuran->map(function($a, $i){
        return [
            'no'=>$i+1,
            'tanggal' => $a->tanggal,
            'angsuran' => $a->cicilan_ke ?? ($i+1),
            'pokok' => (float) $a->bayar_pokok,
            'jasa' => (float) $a->bayar_bunga,
            'total'=>(float) ($a->bayar_pokok + $a->bayar_bunga) ,
        ];
    })->toArray();

    $data = [
        // 'no_pinjaman' => $pinjaman->id_pinjaman,
        // 'no_anggota' => str_pad($pinjaman->nasabah->id_nasabah,5,'0',STR_PAD_LEFT),
        // 'nama' => $pinjaman->nasabah->nama,
        // 'jumlah_pinjaman' => $pinjaman->total_pinjaman,
        // 'tenor' => $pinjaman->pengajuan->tenor,
        // 'tgl_pengajuan' => $pinjaman->pengajuan->tanggal_pengajuan,
        // 'tgl_pelunasan' => $pinjaman->tanggal_pelunasan ? $pinjaman->tanggal_pelunasan->format('Y-m-d') : null,
        // 'status_lunas' => $pinjaman->status == 'aktif' ? 'Tidak' : 'Lunas',
        'pinjaman'=>$pinjaman,
        'angsuran' => $angsuran,
        'tanggal_cetak' => date('d-m-Y'),
        'koperasi_name' => 'Koperasi Sinar Murni Sejahtera',
        'koperasi_address' => 'Jl. Raya Bumi Indah City Blok Ryc R No. 5, Pasar Kemis - Tangerang Banten',
        'logo' => 'logo.png',
    ];
    // return view('pdf.historyangsuran',$data);
   $html = view('pdf.historyangsuran', $data)->render();

    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4-P', // LANDSCAPE
        'margin_top' => 10,
        'margin_bottom' => 10,
        'margin_left' => 20,
        'margin_right' => 10,
        'default_font' => 'dejavusans'
    ]);

    $mpdf->WriteHTML($html);

    return response($mpdf->Output(
        'Riwayat-Pembayaran.pdf',
        'I'
    ))->header('Content-Type', 'application/pdf');
}

public function cetakPerjanjian($id){
    $data = Pengajuan::with('rekening.nasabah')->where('id_pengajuan',$id)->first();
    $user = User::where('jabatan','Pimpinan')->first();
    
    $html = view('pdf.sphutang', compact('data','user'))->render();

    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4-P', // LANDSCAPE
        'margin_top' => 10,
        'margin_bottom' => 20,
        'margin_left' => 10,
        'margin_right' => 10,
        'default_font' => 'dejavusans'
    ]);
$mpdf->SetFooter('Halaman {PAGENO} dari {nbpg}');
    $mpdf->WriteHTML($html);

    return response($mpdf->Output(
        'Riwayat-Pembayaran.pdf',
        'I'
    ))->header('Content-Type', 'application/pdf');
    // $pdf = PDF::loadView('pdf.sphutang', compact('data'))
    //     ->setPaper('A4')
    //     ->setOption('enable-local-file-access', true)
    //     ->setOption('encoding', 'utf-8');

    // return $pdf->inline("SP_Hutang_{$data->rekening[0]->nasabah[0]->nama}.pdf");
}

public function bukuBesarRekap(Request $request){
     $result = [];
        $akunList = Akun::where('status','aktif')->orderBy('kode_akun')->get();
        foreach ($akunList as $akun) {

            $jurnal = Jurnal::where('id_akun',$akun->id_akun)
                ->when($request->tanggal_awal && $request->tanggal_akhir, fn($q)=>
                    $q->whereBetween('tanggal_transaksi',[
                        $request->tanggal_awal,
                        $request->tanggal_akhir
                    ])
                )->get();

            $totalDebet = $jurnal->sum('v_debet');
            $totalKredit = $jurnal->sum('v_kredit');

            $saldo = in_array($akun->tipe_akun,['Kewajiban','Modal','Pendapatan'])
                ? $totalKredit - $totalDebet
                : $totalDebet - $totalKredit;

            $result[] = [
                $akun->kode_akun,
                $akun->nama_akun,
                $totalDebet,
                $totalKredit,
                $saldo
            ];
        }
    $html = view('pdf.bukubesarrekap', compact('result'))->render();

    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'margin_top' => 10,
        'margin_bottom' => 10,
    ]);

    $mpdf->WriteHTML($html);
    return $mpdf->Output('saldo-akun.pdf', 'I');
}
public function bukuBesarAkun(Request $request){
      $akunId  = $request->id_akun;
        $tglAwal = $request->tanggal_awal;
        $tglAkhir= $request->tanggal_akhir;

        $akun = Akun::findOrFail($akunId);
        $saldo = 0;

        // Saldo Awal
        if ($tglAwal) {
            $awal = Jurnal::where('id_akun',$akunId)
                ->whereDate('tanggal_transaksi','<',$tglAwal)->get();

            foreach ($awal as $r) {
                $saldo += in_array($akun->tipe_akun,['Kewajiban','Modal','Pendapatan'])
                        ? ($r->v_kredit - $r->v_debet)
                        : ($r->v_debet - $r->v_kredit);
            }
        }

        $rows = Jurnal::where('id_akun',$akunId)
            ->when($tglAwal && $tglAkhir, fn($q)=>
                $q->whereBetween('tanggal_transaksi',[$tglAwal,$tglAkhir])
            )
            ->orderBy('tanggal_transaksi')
            ->get();

        $data = [];

        // Baris saldo awal
        if ($tglAwal) {
            $data[] = [$tglAwal,'Saldo Awal',0,0,$saldo];
        }

        foreach ($rows as $r) {
            $saldo += in_array($akun->tipe_akun,['Kewajiban','Modal','Pendapatan'])
                        ? ($r->v_kredit - $r->v_debet)
                        : ($r->v_debet - $r->v_kredit);

            $data[] = [
                $r->tanggal_transaksi,
                $r->keterangan,
                $r->v_debet,
                $r->v_kredit,
                $saldo
            ];
        }

        
    $html = view('pdf.bukubesarakun', compact('data','akun'))->render();

    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'margin_top' => 10,
        'margin_bottom' => 10,
    ]);

    $mpdf->WriteHTML($html);
    return $mpdf->Output('saldo-akun.pdf', 'I');
}

public function penyebut($Nilai) {
		$Nilai = abs($Nilai);
		$huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
		$temp = "";
		if ($Nilai < 12) {
			$temp = " ". $huruf[$Nilai];
		} else if ($Nilai <20) {
			$temp = $this->penyebut($Nilai - 10). " Belas";
		} else if ($Nilai < 100) {
			$temp = $this->penyebut($Nilai/10)." Puluh". $this->penyebut($Nilai % 10);
		} else if ($Nilai < 200) {
			$temp = " Seratus" . $this->penyebut($Nilai - 100);
		} else if ($Nilai < 1000) {
			$temp = $this->penyebut($Nilai/100) . " Ratus" . $this->penyebut($Nilai % 100);
		} else if ($Nilai < 2000) {
			$temp = " Seribu" . $this->penyebut($Nilai - 1000);
		} else if ($Nilai < 1000000) {
			$temp = $this->penyebut($Nilai/1000) . " Ribu" . $this->penyebut($Nilai % 1000);
		} else if ($Nilai < 1000000000) {
			$temp = $this->penyebut($Nilai/1000000) . " Juta" . $this->penyebut($Nilai % 1000000);
		} else if ($Nilai < 1000000000000) {
			$temp = $this->penyebut($Nilai/1000000000) . " Milyar" . $this->penyebut(fmod($Nilai,1000000000));
		} else if ($Nilai < 1000000000000000) {
			$temp = $this->penyebut($Nilai/1000000000000) . " Trilyun" . $this->penyebut(fmod($Nilai,1000000000000));
		}     
		return $temp;
	}
public function terbilang($Nilai) {

		if($Nilai<0) {
			$hasil = "minus ". trim($this->penyebut($Nilai))." ";
		}
		elseif ($Nilai==0) {
			$hasil = "-";	
		} 
		else{ 
			$hasil = trim($this->penyebut($Nilai))." ";
		}     		
		return $hasil;
	}
    
public function cetakPenarikan(Request $request)
{
    
    $s = Simpanan::with('rekening.nasabah')->where('id_simpanan',$request->id)->first();
    $data = [
        'no_transaksi'   => $s->no_jurnal,
        'nama'           => $s->rekening->nasabah[0]->nama,
        'no_anggota'     => str_pad($s->rekening->id_nasabah,5,'0',STR_PAD_LEFT),
        'tanggal'        => $s->tanggal,
        'jumlah'         => $s->v_debit,
        'terbilang'      => $this->terbilang($s->v_debit),
    ];

    $html = view('pdf.buktipenarikan', compact('data'))->render();

    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'margin_top' => 10,
        'margin_bottom' => 10,
    ]);

    $mpdf->WriteHTML($html);
    return $mpdf->Output('bukti-penarikan-'.$s->rekening->nasabah[0]->nama.'.pdf','I');
}


}
