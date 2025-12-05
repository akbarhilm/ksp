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

class JurnalExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function headings(): array
    {
        return ['Tanggal','No Jurnal','Akun','Keterangan','Debet','Kredit'];
    }

    public function array(): array
    {
        $tglAwal = $this->request->tanggal_awal;
        $tglAkhir= $this->request->tanggal_akhir;

        $query = Jurnal::with('akun')
            ->when($tglAwal && $tglAkhir, fn($q)=>
                $q->whereBetween('tanggal_transaksi',[$tglAwal,$tglAkhir])
            )
            ->orderBy('tanggal_transaksi','desc')
            ->orderBy('no_jurnal','desc')
            ->get();

        $data = [];
        foreach ($query as $r) {
            $data[] = [
                $r->tanggal_transaksi,
                $r->no_jurnal,
                $r->akun->kode_akun.' - '.$r->akun->nama_akun,
                $r->keterangan,
                $r->v_debet,
                $r->v_kredit
               
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
            'B' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}
