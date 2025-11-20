<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Surat Perjanjian Hutang</title>
    {{-- <link rel="stylesheet" href="{{ public_path('css/pdf.css') }}"> --}}
    <style>
        body {
    font-family: sans-serif;
    font-size: 12px;
    line-height: 1.35;
}


h1, h2, h3, h4, h5, h6 {
    margin: 0;
    padding: 0;
}

.text-center { text-align: center; }
.text-right { text-align: right; }
.text-left { text-align: left; }
.text-justify { text-align: justify; }

.text-sm { font-size: 14px; }
.text-xs { font-size: 12px; }

.table {
    width: 100%;
    border-collapse: collapse;
}

.table td {
    padding: 3px 4px;
    vertical-align: top;
}
.pasal td:first-child {
    width: 20px;
    vertical-align: top;
}

.pasal td:last-child {
    vertical-align: top;
}

.table-borderless td {
    border: none !important;
}

.underline { text-decoration: underline; }

.mt-2 { margin-top: 8px; }
.mt-3 { margin-top: 12px; }
.mt-4 { margin-top: 16px; }
.mt-5 { margin-top: 20px; }

.mb-0 { margin-bottom: 0; }
.mb-2 { margin-bottom: 8px; }
.mb-4 { margin-bottom: 16px; }

.page {
    width: 100%;
    max-width: 210mm;
    margin: 0 auto;
    padding: 15px 20px;
    background: white;
}

        </style>
</head>

<body>
    <?php 
    if (!function_exists('penyebut')) {
function penyebut($Nilai) {
		$Nilai = abs($Nilai);
		$huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
		$temp = "";
		if ($Nilai < 12) {
			$temp = " ". $huruf[$Nilai];
		} else if ($Nilai <20) {
			$temp = penyebut($Nilai - 10). " Belas";
		} else if ($Nilai < 100) {
			$temp = penyebut($Nilai/10)." Puluh". penyebut($Nilai % 10);
		} else if ($Nilai < 200) {
			$temp = " Seratus" . penyebut($Nilai - 100);
		} else if ($Nilai < 1000) {
			$temp = penyebut($Nilai/100) . " Ratus" . penyebut($Nilai % 100);
		} else if ($Nilai < 2000) {
			$temp = " Seribu" . penyebut($Nilai - 1000);
		} else if ($Nilai < 1000000) {
			$temp = penyebut($Nilai/1000) . " Ribu" . penyebut($Nilai % 1000);
		} else if ($Nilai < 1000000000) {
			$temp = penyebut($Nilai/1000000) . " Juta" . penyebut($Nilai % 1000000);
		} else if ($Nilai < 1000000000000) {
			$temp = penyebut($Nilai/1000000000) . " Milyar" . penyebut(fmod($Nilai,1000000000));
		} else if ($Nilai < 1000000000000000) {
			$temp = penyebut($Nilai/1000000000000) . " Trilyun" . penyebut(fmod($Nilai,1000000000000));
		}     
		return $temp;
	}
}
if (!function_exists('terbilang')) {
    function terbilang($Nilai) {

		if($Nilai<0) {
			$hasil = "minus ". trim(penyebut($Nilai))." ";
		}
		elseif ($Nilai==0) {
			$hasil = "-";	
		} 
		else{ 
			$hasil = trim(penyebut($Nilai))." ";
		}     		
		return $hasil;
	}
}
	
	
			
			
		
	?>
<div class="page">
    <!-- TITLE -->
    <div class="text-center mb-2 mt-3">
        <h3 class="underline">SURAT PERJANJIAN HUTANG PIUTANG</h3>
        <div class="text-xs">Nomor Anggota : {{str_pad($data->rekening[0]->nasabah[0]->id_nasabah,5,'0',STR_PAD_LEFT)}}</div>
    </div>

    <p class="text-sm">Yang bertanda tangan di bawah ini:</p>

    <!-- BIODATA -->
    <table class="table table-borderless text-sm">
        <tr><td>1.</td><td>Nama</td><td>:</td><td>{{strtoupper($data->rekening[0]->nasabah[0]->nama) }}</td></tr>
        <tr><td></td><td>Alamat</td><td>:</td><td>{{strtoupper($data->rekening[0]->nasabah[0]->alamat) }}</td></tr>
        <tr><td></td><td>NIK</td><td>:</td><td>{{strtoupper($data->rekening[0]->nasabah[0]->nik) }}</td></tr>
        <tr><td></td><td>Pekerjaan</td><td>:</td><td>{{strtoupper($data->rekening[0]->nasabah[0]->pekerjaan) }}</td></tr>
        <tr><td></td><td>No HP</td><td>:</td><td>{{strtoupper($data->rekening[0]->nasabah[0]->no_telp) }}</td></tr>
        <tr>
            <td></td>
            <td colspan="3"><b>Selanjutnya disebut PIHAK KESATU atau YANG BERHUTANG</b></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3">
                <p class="text-justify text-sm">
                    Kepala Cabang Koperasi KSP SINAR MURNI SEJAHTERA PASAR KEMIS dalam hal tersebut 
                    dan atas nama Koperasi “SINAR MURNI SEJAHTERA” Beralamat DI JALAN RAYA BUMI INDAH 
                    BLOK RYER PASAR KEMIS - TANGERANG, Selanjutnya disebut PIHAK KEDUA atau KOPERASI.
                </p>
            </td>
        </tr>
    </table>

    <!-- SUBTITLE -->
    <div class="text-center mt-2 mb-2">
        <b class="underline text-sm">KEDUA BELAH PIHAK MENGADAKAN PERJANJIAN SEBAGAI BERIKUT</b>
    </div>

    <!-- PASAL -->
    <table class="table table-borderless text-sm pasal">

        <tr>
            <td><p class="text-justify">a.</p></td>
            <td>
                <p class="text-justify">
                    PIHAK KESATU mengaku telah berhutang dari PIHAK KEDUA sebesar 
                    <b>Rp. {{number_format($data->jumlah_pencairan,2,',','.')}} ({{ terbilang($data->jumlah_pencairan)}}Rupiah)</b>.
                </p>
            </td>
        </tr>

        <tr>
            <td><p class="text-justify">b.</p></td>
            <td>
                <p class="text-justify">
                    Untuk pengembalian pinjaman Tersebut <b>PIHAK KESATU</b> bersedia diambil angsurannya 
melalui ATM oleh <b>PIHAK KEDUA</b> sesuai dengan angsuran pinjaman yang telah ditetapkan & 
disepakati kedua belah pihak sebesar <b>Rp {{number_format(($data->jumlah_pencairan/$data->tenor*(($data->bunga*$data->tenor/100)+1)),2,',','.')}}
    ({{terbilang(($data->jumlah_pencairan*(($data->bunga/100)+1))) }} Rupiah)</b>.
                </p>
            </td>
        </tr>

        <tr>
            <td><p class="text-justify">c.</p></td>
            <td>
                <p class="text-justify">
                    Setiap bulan selama {{$data->tenor}} kali angsuran mulai bulan {{ (new IntlDateFormatter('id_ID', IntlDateFormatter::NONE, IntlDateFormatter::NONE, null, null, 'MMMM yyyy'))
        ->format(new DateTime('+1 month')) }} sampai dengan bulan {{ (new IntlDateFormatter('id_ID', IntlDateFormatter::NONE, IntlDateFormatter::NONE, null, null, 'MMMM yyyy'))
        ->format(new DateTime("+".($data->tenor+1)."month")) }}
atau sampai hutang PIHAK KESATU lunas. Dengan ketentuan apabila terjadi 
keterlambatan pembayaran maka setiap bulannya akan dikenakan denda sebesar 1% 
perbulan atau 0.1% perhari dari saldo pinjaman.

                </p>
            </td>
        </tr>

        <tr>
            <td><p class="text-justify">d.</p></td>
            <td>
                <p class="text-justify">
                    PIHAK KESATU menyetujui potongan simpanan Rp. {{number_format($data->simpanan_wajib,0,',','.')}} dan biaya tata 
laksana/administrasi Rp. {{number_format($data->admin,0,',','.')}} Atas dasar pinjaman tersebut, saya PIHAK KESATU dengan 
sukarela menitip dengan PIHAK KEDUA berupa 
                </p>
            </td>
        </tr>
    </table>

    <!-- BARANG JAMINAN -->
    <table class="table table-borderless text-sm mt-1" style="margin-left: 30px">
        @foreach ($data->jaminan as $j)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$j->jenis_jaminan}}</td>
                <td>{{$j->keterangan}}</td>
            </tr>
        @endforeach
     
    </table>

    <table class="table table-borderless text-sm mt-4">
        <tr>
            <td><p class="text-justify"></p></td>
            <td>
                <p class="text-justify">
                     Saya tidak keberatan gaji saya diambil melalui ATM dan sisanya saya ambil dari <b>KSP SINAR 
MURNI – SEJAHTERA PASAR KEMIS<b> tunai/ non tunai dipotong cicilan pinjaman perbulan 
sampai pinjaman saya lunas . 
                </p>
            </td>
        </tr>

        <tr>
            <td><p class="text-justify">e.</p></td>
            <td><p class="text-justify"> Untuk angsuran pada point B, PIHAK KESATU menyetujui diambil gaji pertama setelah 
perjanjian akad kredit ini di buat.</p></td>
        </tr>

        <tr>
            <td><p class="text-justify">f.</p></td>
            <td><p class="text-justify">Bilamana PIHAK KESATU tidak bekerja lagi atau di PHK dari pekerjaannya, maka PIHAK KESATU 
harus melunasi sisa utangnya sesuai dengan perjanjian Kredit</p></td>
        </tr>

        <tr>
            <td><p class="text-justify">g.</p></td>
            <td><p class="text-justify">Apabila PIHAK KESATU melanggar Surat Perjanjian ini, maka bersedia dituntut di Pengadilan 
Negeri Kab. Tangerang, dengan catatan seluruh biaya perkara ditanggung oleh PIHAK KESATU</p></td>
        </tr>

        <tr>
            <td><p class="text-justify">h.</p></td>
            <td><p class="text-justify">Dan apabila ada pinjaman baik dari BANK atau Pihak Lain yang masuk ke rekening yang 
saya jaminkan kepada KSP SINAR MURNI, maka saya siap melunasi hutang di KOPERASI 
SIMPAN PINJAM SINAR MURNI Secara Flat tidak ada pengurangan pokok maupun bunga.</p></td>
        </tr>

        <tr>
            <td><p class="text-justify">i.</p></td>
            <td>
                <p class="text-justify">
                     PIHAK KESATU berjanji tidak akan mengambil BERKAS diatas yang saya titipkan sebelum pinjaman 
saya lunas dan tidak akan merekayasa system pengambilan gaji seperti merubah/memblokir 
No.Rekening, No.Pin ataupun <b>MOBILE BANKING, SMS BANKING</b> dan <b>INTERNET BANKING</b>  yang 
menyebabkan proses pembayaran angsuran ke PIHAK KEDUA terhambat.
                </p>
            </td>
        </tr>

        <tr>
            <td><p class="text-justify">j.</p></td>
            <td>
                <p class="text-justify">
                   Bilamana PIHAK KESATU tidak bekerja lagi atau PHK (PEMUTUSAN HUBUNGAN KERJA) dari 
pekerjaannya, maka PIHAK KESATU harus melunasi sisa utangnya dari pesangon dan SALDO 
JAMSOSTEK ataupun dari hal lain dengan jumlah sisa hutangnya yang tertera di perjanjian 
kredit
                </p>
            </td>
        </tr>

        <tr>
            <td><p class="text-justify">k.</p></td>
            <td>
                <p class="text-justify">
                    Apabila PIHAK KESATU melanggar Surat Perjanjian ini, maka bersedia dituntut di Pengadilan 
Negeri Tangerang, dengan catatan seluruh biaya perkara ditanggung oleh PIHAK KESATU.
                </p>
            </td>
        </tr>
    </table>

    <!-- PENUTUP -->
    <p class="text-justify mt-4 text-sm">
        Demikian Surat Perjanjian ini dibuat dengan pikiran sehat dan tenang tanpa ada paksaan dari pihak 
lain , coretan dan penambahan dianggap sah oleh kedua belah pihak , lalu ditanda tangani masing
masing pihak dengan diberi materai secukupnya untuk dijadikan bukti yang sah.
    </p>

    <!-- SIGNATURES -->
    <table class="table table-borderless text-sm mt-4">
        <tr>
            <td class="text-center">KSP SINAR MURNI</td>
            <td class="text-center">Tangerang, {{ (new IntlDateFormatter('id_ID', IntlDateFormatter::NONE, IntlDateFormatter::NONE, null, null, 'dd MMMM yyyy'))
        ->format(new DateTime()) }}</td>
        </tr>
        <tr>
            <td style="height: 70px"></td>
            <td style="height: 70px"></td>
        </tr>
        <tr>
            <td class="text-center">ROHMAYATI<br>(PIMPINAN CABANG)</td>
            <td class="text-center">{{strtoupper($data->rekening[0]->nasabah[0]->nama) }}<br>(PIHAK KESATU)</td>
        </tr>
        <tr>
            <td style="height: 70px"></td>
            <td style="height: 70px"></td>
        </tr>
        <tr>
            <td></td>
            <td class="text-center">MOCH EDWIN APRIZA<br>(PENANGGUNG JAWAB)</td>
        </tr>
    </table>

</div>
</body>
</html>
