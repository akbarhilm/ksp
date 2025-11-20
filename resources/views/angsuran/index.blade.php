<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="angsuran" menuParent="loan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Angsuran"></x-navbars.navs.auth>
<div class="container">
    <h2>Pembayaran Angsuran Pinjaman </h2>

    <div class="card mb-4">
        <div class="card-body">
            <h5>Informasi Pinjaman</h5>
            <p><strong>Nasabah:</strong> {{ str_pad($pinjaman->id_nasabah , 5, '0', STR_PAD_LEFT);}}</p>
            <p><strong>Total Pinjaman:</strong> {{ number_format($pinjaman->total_pinjaman,0) }}</p>
            <p><strong>Sisa Pokok:</strong> {{ number_format($pinjaman->sisa_pokok,0) }}</p>
            <p><strong>Sisa Bunga:</strong> {{ number_format($pinjaman->sisa_bunga,0) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($pinjaman->status) }}</p>
        </div>
    </div>

    <!-- Form Pembayaran -->
    <div class="card mb-4">
    <div class="card-body">
        <h5>Input Pembayaran Angsuran</h5>

        {{-- Data dari backend --}}
        @php
            $jumlahPinjaman = $pinjaman->total_pinjaman;
            $sukuBunga = $pinjaman->pengajuan->bunga; // persen
             $tenor = $pinjaman->pengajuan->tenor;    // bulan
        @endphp

        <form action="{{ route('angsuran.store', $pinjaman->id_pinjaman) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Total Bayar</label>
                <input type="number" id="total_bayar" name="total_bayar" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Pokok Dibayar</label>
                <input type="number" name="pokok" id="pokok" class="form-control" readonly required>
            </div>

            <div class="mb-3">
                <label>Bunga Dibayar</label>
                <input type="number" name="bunga" id="bunga" class="form-control" readonly>
            </div>
            <div class="mb-3">
                <label>Denda</label>
                 @php
                                        $denda = \App\Helpers\PinjamanHelper::hitungDenda($pinjaman->id_pinjaman);
                                    @endphp
                <input type="number" name="denda" id="denda" value="{{number_format($denda,0,',','.')}}" class="form-control" readonly>
            </div>
             <div class="mb-3">
                <label>Simpanan Pokok</label>
                <input type="number" name="simpanan" id="simpanan" class="form-control input-jumlah" onchange="addtototal()" >
            </div>

            <div class="mb-3">
                <label>Tanggal Bayar</label>
                <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="mb-3">
        <label>Metode Pembayaran</label>
        <select name="metode" class="form-control" required>
            <option value="">-- Pilih Metode --</option>
            <option value="ATM">ATM</option>
            <option value="Auto Debit">Auto Debit</option>
            <option value="Cash">Cash</option>
        </select>
    </div>
            

            <button type="submit" class="btn btn-info">Simpan Pembayaran</button>
        </form>
    </div>
</div>




    <!-- History Pembayaran -->
    <div class="card">
        <div class="card-body">
            <h5>History Pembayaran</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Total Bayar</th>
                        <th>Pokok</th>
                        <th>Bunga</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $h)
                    <tr>
                        <td>{{ $h->tanggal }}</td>
                        <td class="text-end">{{ number_format($h->total_bayar,0) }}</td>
                        <td class="text-end">{{ number_format($h->bayar_pokok,0) }}</td>
                        <td class="text-end">{{ number_format($h->bayar_bunga,0) }}</td>
                        <td>{{ 'Pembayaran Angsuran ke '.$h->cicilan_ke }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada pembayaran</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
    </main>
    <x-plugins></x-plugins>
            @push('js')
<script>
    function addtototal(){
       let simpanan =  toNumber($("#simpanan").val());
        let total = toNumber(document.getElementById('total_bayar').value) 
        document.getElementById('total_bayar').value  = total + simpanan
    }
     const jumlahPinjaman = {{ $jumlahPinjaman }};  
    const sukuBunga = {{ $sukuBunga }};  
    const tenor = {{ $tenor }}; 
    const denda = {{$denda}}          
    // Hitung bunga bulanan
    const bungaPerBulan = jumlahPinjaman * (sukuBunga / 100);

    // Hitung pokok per bulan
    const pokokPerBulan = jumlahPinjaman / tenor;

    // Total bayar bulanan
    const totalBulanan = pokokPerBulan + bungaPerBulan + denda ;

    // Set otomatis ke form
    document.getElementById('total_bayar').value = Math.round(totalBulanan);
    document.getElementById('pokok').value = Math.round(pokokPerBulan);
    document.getElementById('bunga').value = Math.round(bungaPerBulan);
</script>
@endpush
</x-layout>
