<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use App\Models\Pengajuan;
use App\Models\PengajuanJaminan;
use App\Models\Pinjaman;
use App\Models\User;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Mpdf\Mpdf;
use App\Helpers\Helper;

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

            $resort = $p->nasabah->kode_resort ?? 'UNKNOWN';

            // hitung hari tunggakan & kolek
            $hariTelat = Helper::hariTunggakan($p->id_pinjaman, $tanggal);
            $kolek     = Helper::getKolektibilitas($hariTelat);

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
            if (in_array($kolek, ['C3','C4','C5'])) {
                $dataResort[$resort]['total_npl'] += $sisa;
            }

            $dataResort[$resort]['detail'][] = [
                'nasabah' => $p->nasabah->nama,
                'sisa'    => $sisa,
                'hari'    => $hariTelat,
                'kolek'   => $kolek,
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
    public function nplindex(Request $request){
         $tanggal = $request->tanggal ?? date('Y-m-d');

    $pinjaman = Pinjaman::where('status', 'aktif')->get();

    $totalKredit = 0;
    $totalNPL    = 0;

    $detail = [];

    foreach ($pinjaman as $p) {

        // hitung hari tunggakan (pakai fungsi kamu)
        $data = PinjamanHelper::hitungDenda($p->id_pinjaman);
        // $kolek     = Helper::getKolektibilitas($hariTelat);

        $sisa = $p->sisa_pokok;

        $totalKredit += $sisa;

        if (in_array($data['kolek'], ['C3','C4','C5'])) {
            $totalNPL += $sisa;
        }

        $detail[] = [
            'nasabah' => $p->nasabah->nama,
            'sisa'    => $sisa,
            'hari'    => $data['haritelat'],
            'kolek'   => $data['kolek']
        ];
    }

    $rasioNPL = $totalKredit > 0
        ? round(($totalNPL / $totalKredit) * 100, 2)
        : 0;

    return view('npl.index', compact(
        'tanggal',
        'totalKredit',
        'totalNPL',
        'rasioNPL',
        'detail'
    ));
    }

public function cetakJaminan($id)
{
    $pinjaman = Pinjaman::with('nasabah')->where('id_pinjaman',$id)->first();
    $user = User::where('jabatan','Kepala Cabang')->first();
    $jaminan = PengajuanJaminan::where('id_pengajuan',$pinjaman->id_pengajuan)->get();
    $data =['nama'=>$pinjaman->nasabah->nama,'alamat'=>$pinjaman->nasabah->alamat,'jaminan'=>[]];
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
        'format' => 'A4-L', // LANDSCAPE
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
    $materai = $pinjaman->pengajuan->materai;
        $provisi = $pinjaman->pengajuan->admin;
        $survey = $pinjaman->pengajuan->survey;
        $asuransi = $pinjaman->pengajuan->asuransi;
        $simpanan_pokok = $pinjaman->pengajuan->simpanan_pokok;





    $data = [
        'no_anggota' => str_pad($pinjaman->nasabah->id,5,'0',STR_PAD_LEFT),
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
        'diterima_bersih' => ($pinjaman->total_pinjaman -$provisi-$survey-$asuransi-$simpanan_pokok),
    ];
   // return view('pdf.pencairan',compact('data'));
   $html = view('pdf.pencairan', compact('data'))->render();

    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4-L', // LANDSCAPE
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
        'format' => 'A4-L', // LANDSCAPE
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

public function cetakPerjanjian($id){
    $data = Pengajuan::with('rekening.nasabah')->where('id_pengajuan',$id)->first();

    $html = view('pdf.sphutang', compact('data'))->render();

    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4-L', // LANDSCAPE
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
    // $pdf = PDF::loadView('pdf.sphutang', compact('data'))
    //     ->setPaper('A4')
    //     ->setOption('enable-local-file-access', true)
    //     ->setOption('encoding', 'utf-8');

    // return $pdf->inline("SP_Hutang_{$data->rekening[0]->nasabah[0]->nama}.pdf");
}


}
