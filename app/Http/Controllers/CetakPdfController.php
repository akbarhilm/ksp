<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use App\Models\Pengajuan;
use App\Models\PengajuanJaminan;
use App\Models\Pinjaman;
use App\Models\User;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

use Illuminate\Http\Request;

class CetakPdfController extends Controller
{

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
    $html =  view('pdf.sttjaminan', compact('data'))->render();

    $pdf = PDF::loadHTML($html)
        ->setPaper('A4')
        ->setOption('encoding','utf-8')
        ->setOption('enable-local-file-access', true)
        ->setOption('margin-top', 10)
        ->setOption('margin-bottom', 15)
        ->setOption('margin-left', 15)
        ->setOption('margin-right', 15)
        ->setOption('enable-local-file-access', true);

    return $pdf->inline("TandaTerimaJaminan-{$data['nama']}.pdf");
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
    $pdf = PDF::loadView('pdf.pencairan', compact('data'))
        ->setPaper('A5', 'landscape')
        ->setOption('enable-local-file-access', true)
        ->setOption('encoding','utf-8');

    return $pdf->inline('Pencairan-'.$pinjaman->nasabah->nama.'.pdf');
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
        'no_pinjaman' => $pinjaman->id_pinjaman,
        'no_anggota' => str_pad($pinjaman->nasabah->id_nasabah,5,'0',STR_PAD_LEFT),
        'nama' => $pinjaman->nasabah->nama,
        'jumlah_pinjaman' => $pinjaman->total_pinjaman,
        'tenor' => $pinjaman->pengajuan->tenor,
        'tgl_pengajuan' => $pinjaman->pengajuan->tanggal_pengajuan,
        'tgl_pelunasan' => $pinjaman->tanggal_pelunasan ? $pinjaman->tanggal_pelunasan->format('Y-m-d') : null,
        'status_lunas' => $pinjaman->status == 'aktif' ? 'Tidak' : 'Lunas',
        'angsuran' => $angsuran,
        'tanggal_cetak' => date('d-m-Y'),
        'koperasi_name' => 'Koperasi Sinar Murni Sejahtera',
        'koperasi_address' => 'Jl. Raya Bumi Indah City Blok Ryc R No. 5, Pasar Kemis - Tangerang Banten',
        'logo' => 'logo.png',
    ];
    // return view('pdf.historyangsuran',$data);
    $pdf = PDF::loadView('pdf.historyangsuran', $data)
        ->setPaper('A4')
        ->setOption('enable-local-file-access', true)
        ->setOption('encoding', 'utf-8');

    return $pdf->inline("Riwayat-Pembayaran-{$pinjaman->id}.pdf");
}

public function cetakPerjanjian($id){
    $data = Pengajuan::with('rekening.nasabah')->where('id_pengajuan',$id)->first();
    $pdf = PDF::loadView('pdf.sphutang', compact('data'))
        ->setPaper('A4')
        ->setOption('enable-local-file-access', true)
        ->setOption('encoding', 'utf-8');

    return $pdf->inline("SP_Hutang_{$data->rekening[0]->nasabah[0]->nama}.pdf");
}


}
