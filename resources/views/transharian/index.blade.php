<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="harian" menuParent="laporan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Transaksi Harian"></x-navbars.navs.auth>
        <!-- End Navbar -->

<div class="container mt-4">

    <h3 class="mb-3">Laporan Transaksi Harian (Per User Entry)</h3>

    {{-- FORM FILTER --}}
    <form method="GET" action="{{ route('transaksi.harian') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary">Tampilkan</button>
        </div>
    </form>

    {{-- ========================== TABEL SIMPANAN ========================== --}}
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <strong>Transaksi Simpanan</strong>
        </div>
        <div class="card-body p-0">
            @if($simpanan->count() > 0)
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Keterangan</th>
                        <th class="text-end">Debit</th>
                        <th class="text-end">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($simpanan as $s)
                    <tr>
                        <td>{{ $s->tanggal }}</td>
                        <td>{{ $s->jenis }}</td>
                        <td>{{ $s->keterangan }}</td>
                        <td class="text-end">{{ number_format($s->v_debit,0) }}</td>
                        <td class="text-end">{{ number_format($s->v_kredit,0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="p-3">Tidak ada transaksi simpanan.</div>
            @endif
        </div>
    </div>

    {{-- ========================== TABEL PENGAJUAN ========================== --}}
    <div class="card mb-4">
        <div class="card-header bg-warning text-white">
            <strong>Transaksi Pengajuan Pinjaman</strong>
        </div>
        <div class="card-body p-0">
            @if($pengajuan->count() > 0)
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nasabah</th>
                        <th>Bunga</th>
                        <th class="text-end">Plafon</th>
                        <th class="text-end">Tenor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengajuan as $p)
                    <tr>
                        <td>{{ $p->tanggal_pengajuan }}</td>
                        <td>{{ str_pad($p->rekening[0]->id_nasabah, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $p->bunga.'%' }}</td>
                        <td class="text-end">{{ number_format($p->jumlah_pengajuan,0) }}</td>
                        <td class="text-end">{{ $p->tenor }} bulan</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="p-3">Tidak ada transaksi pengajuan.</div>
            @endif
        </div>
    </div>

    {{-- ========================== TABEL ANGSURAN ========================== --}}
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <strong>Transaksi Angsuran</strong>
        </div>
        <div class="card-body p-0">
            @if($angsuran->count() > 0)
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Nasabah</th>
                        <th class="text-end">Bayar Pokok</th>
                        <th class="text-end">Bayar Bunga</th>
                        <th class="text-end">Bayar Denda</th>
                        <th class="text-end">Total Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalpokok = 0;
                            $totalbunga = 0;
                            $totaldenda = 0;
                            $totalbayara = 0;
                            @endphp
                    @foreach($angsuran as $a)
                    <tr>
                        <td>{{ $a->tanggal }}</td>
                        <td>{{  str_pad($a->pinjaman->id_nasabah, 5, '0', STR_PAD_LEFT).' / '.$a->pinjaman->nasabah->nama ?? '-' }}</td>
                        <td class="text-end">{{ number_format($a->bayar_pokok,0) }}</td>
                        <td class="text-end">{{ number_format($a->bayar_bunga,0) }}</td>
                        <td class="text-end">{{ number_format($a->bayar_denda,0) }}</td>
                        <td class="text-end">{{ number_format($a->total_bayar,0) }}</td>
                        {{ $totalpokok += $a->bayar_pokok; }}
                        {{ $totalbunga += $a->bayar_bunga; }}

                        {{ $totaldenda += $a->bayar_denda; }}

                        {{ $totalbayar += $a->total_bayar; }}

                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan='2'>
                            Total
                        </th>
                        <th class='text-end'>{{ number_format($totalpokok,0) }}</th>
                        <th class='text-end'>{{ number_format($totalbunga,0) }}</th>
                        <th class='text-end'>{{ number_format($totaldenda,0) }}</th>
                        <th class='text-end'>{{ number_format($totalbayar,0) }}</th>
                    </tr>
                </tfoot>
            </table>
            @else
            <div class="p-3">Tidak ada transaksi angsuran.</div>
            @endif
        </div>
    </div>

</div>
    </main>

 @push('js')
<script>
function lihat() {
    var tanggal = $("#tanggal").val();

    if (!tanggal) {
        alert("Pilih tanggal terlebih dahulu!");
        return;
    }

    $.ajax({
        url: "{{ route('transaksi.harian.view') }}",
        type: "GET",
        data: { 'tanggal': tanggal },
        success: function(data) {

            $('#result').html('');

            let totalDebit = 0;
            let totalKredit = 0;

            // --- SIMPANAN ---
            data.simpanan.forEach(function(s) {

                totalDebit += Number(s.v_debit);
                totalKredit += Number(s.v_kredit);

                $('#result').append(`
                    <tr>
                        <td>${s.tanggal}</td>
                        <td>Simpanan</td>
                        <td>${s.keterangan ?? '-'}</td>
                        <td class="text-end">${Number(s.v_debit).toLocaleString()}</td>
                        <td class="text-end">${Number(s.v_kredit).toLocaleString()}</td>
                    </tr>
                `);
            });

            // --- PENGAJUAN ---
            data.pengajuan.forEach(function(p) {

                // Pengajuan → uang keluar = debit
                let debit = Number(p.jumlah ?? 0);
                let kredit = 0;

                totalDebit += debit;

                $('#result').append(`
                    <tr>
                        <td>${p.tanggal}</td>
                        <td>Pengajuan Pinjaman</td>
                        <td>${p.keperluan ?? '-'}</td>
                        <td class="text-end">${debit.toLocaleString()}</td>
                        <td class="text-end">0</td>
                    </tr>
                `);
            });

            // --- ANGSURAN ---
            data.angsuran.forEach(function(a) {

                // Angsuran = pemasukan (kredit)
                let kredit = Number(a.total_bayar);
                totalKredit += kredit;

                $('#result').append(`
                    <tr>
                        <td>${a.tanggal}</td>
                        <td>Angsuran</td>
                        <td>Pembayaran ke-${a.cicilan_ke}</td>
                        <td class="text-end">0</td>
                        <td class="text-end">${kredit.toLocaleString()}</td>
                    </tr>
                `);
            });

            // --- SALDO AKHIR ---
            let saldo = totalKredit - totalDebit;
            $("#saldoAkhir").text(saldo.toLocaleString());
        }
    });
}
</script>
   @endpush
</x-layout>