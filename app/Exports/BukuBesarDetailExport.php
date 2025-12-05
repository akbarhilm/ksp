<?php

namespace App\Exports;

use App\Models\Akun;
use App\Models\Jurnal;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class BukuBesarDetailExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function headings(): array
    {
        return ['Tanggal','Keterangan','Debet','Kredit','Saldo'];
    }

    public function array(): array
    {
        $akunId  = $this->request->id_akun;
        $tglAwal = $this->request->tanggal_awal;
        $tglAkhir= $this->request->tanggal_akhir;

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

        return $data;
    }

    // Heading bold
    public function styles(Worksheet $sheet)
    {
        return [1 => ['font'=>['bold'=>true]]];
    }

    // Rupiah format
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}
