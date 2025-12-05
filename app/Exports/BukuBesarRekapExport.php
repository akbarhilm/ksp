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

class BukuBesarRekapExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function headings(): array
    {
        return ['Kode Akun','Nama Akun','Total Debet','Total Kredit','Saldo'];
    }

    public function array(): array
    {
        $result = [];
        $akunList = Akun::where('status','aktif')->orderBy('kode_akun')->get();
        foreach ($akunList as $akun) {

            $jurnal = Jurnal::where('id_akun',$akun->id_akun)
                ->when($this->request->tanggal_awal && $this->request->tanggal_akhir, fn($q)=>
                    $q->whereBetween('tanggal_transaksi',[
                        $this->request->tanggal_awal,
                        $this->request->tanggal_akhir
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

        return $result;
    }

    // Heading Bold
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]]
        ];
    }

    // Format Rupiah
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}
