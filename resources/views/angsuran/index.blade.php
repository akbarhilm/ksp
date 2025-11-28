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
                <input type="text" id="total_bayar" name="total_bayar" class="form-control input-jumlah" required readonly>
            </div>

            <div class="mb-3">
                <label>Pokok Dibayar</label>
                <input type="text" name="pokok" id="pokok" class="form-control input-jumlah" readonly required>
            </div>

            <div class="mb-3">
                <label>Bunga Dibayar</label>
                <input type="text" name="bunga" id="bunga" class="form-control input-jumlah" readonly>
            </div>
            <div class="mb-3">
                <label>Denda</label>
                 @php
                                        $denda = \App\Helpers\PinjamanHelper::hitungDenda($pinjaman->id_pinjaman);
                                    @endphp
                <input type="text" name="denda" id="denda" value={{number_format($denda['denda'],0,',','.')}} class="form-control input-jumlah" onchange="adddenda()" >
            </div>
             <div class="mb-3">
                <label>Simpanan Wajib</label>
                <input type="text" name="simpanan" id="simpanan" class="form-control input-jumlah" onchange="adddenda()" required >
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
            
            @php
    // cek apakah sudah ada pembayaran bulan ini
    $bulanIni = date('Y-m');
    $sudahBayarBulanIni = $history->contains(function($h) use ($bulanIni) {
        return date('Y-m', strtotime($h->tanggal)) === $bulanIni;
    });
@endphp
            <button type="submit" class="btn btn-info" {{ $sudahBayarBulanIni ? 'disabled' : '' }}>Simpan Pembayaran</button>
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
                        <th>Denda</th>
                        <th>Simpanan</th>
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
                        <td class="text-end">{{ number_format($h->bayar_denda,0) }}</td>
                        <td class="text-end">{{ number_format($h->simpanan,0) }}</td>
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
            @push('js')
<script>

    function angka(angka) {
    if (!angka) return "";
    angka = angka.toString().replace(/[^0-9]/g, ""); // hilangkan non-angka

    return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
    function addtototal(){
       let simpanan =  toNumber($("#simpanan").val());
        let total = toNumber(document.getElementById('total_bayar').value) 
        document.getElementById('total_bayar').value  = angka(total + simpanan)
    }

   function adddenda() {

    // Ambil denda baru dari input user
    let dendaBaru = toNumber($("#denda").val());
    let simpanan =  toNumber($("#simpanan").val());

    // Jika kosong → pakai denda default dari server
   if (!dendaBaru) {
        dendaBaru = 0;
    }
     if (!simpanan) {
        simpanan = 0;
     }

    // Hitung total baru (TANPA memakai total lama)
    let totalBaru = pokokPerBulan + bungaPerBulan + dendaBaru + simpanan;

    // Set ulang total
    document.getElementById('total_bayar').value = angka(totalBaru);
}

     const jumlahPinjaman = {{ $jumlahPinjaman }};  
    const sukuBunga = {{ $sukuBunga }};  
    const tenor = {{ $tenor }}; 
    const dendaDefault = {{$denda['denda']}};          
    // Hitung bunga bulanan
    const bungaPerBulan = jumlahPinjaman * (sukuBunga / 100);

    // Hitung pokok per bulan
    const pokokPerBulan = jumlahPinjaman / tenor;

    // Total bayar bulanan
    const totalBulanan = pokokPerBulan + bungaPerBulan + dendaDefault ;

    // Set otomatis ke form
    document.getElementById('total_bayar').value = angka(totalBulanan);
    document.getElementById('pokok').value = angka(pokokPerBulan);
    document.getElementById('bunga').value = angka(bungaPerBulan);
</script>
@endpush
</x-layout>
