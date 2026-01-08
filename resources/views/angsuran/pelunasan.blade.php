<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="pelunasan" menuParent="loan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Pelunasan"></x-navbars.navs.auth>
<div class="container">
    <h2>Pelunasan Pinjaman </h2>
    <div class="row">
        <div class="col-md-6">
    <div class="card mb-4">
        <div class="card-body">
            <h5>Informasi Pinjaman</h5>
            <p><strong>Anggota:</strong> {{ str_pad($pinjaman->id_nasabah , 5, '0', STR_PAD_LEFT);}}</p>
            <p><strong>Total Pinjaman:</strong> {{ number_format($pinjaman->total_pinjaman,0) }}</p>
            <p><strong>Sisa Pokok:</strong> {{ number_format($pinjaman->sisa_pokok,0) }}</p>
            <p><strong>Sisa Bunga:</strong> {{ number_format($pinjaman->sisa_bunga,0) }}</p>
        </div>
    </div>
        </div>
        <div class="col-md-6">
    <div class="card mb-4">
        <div class="card-body">
            <h5>Informasi Simpanan</h5>
            <p><strong>Anggota:</strong> {{ str_pad($pinjaman->id_nasabah , 5, '0', STR_PAD_LEFT);}}</p>
            <p><strong>Simpanan Pokok:</strong> {{ number_format($simpananpokok,0) }}</p>
            <p><strong>Simpanan Wajib:</strong> {{ number_format($simpananwajib,0) }}</p>
            <p><strong>Total Simpanan:</strong> {{ number_format($simpananpokok+$simpananwajib,0) }}</p>

            
        </div>
    </div>
        </div>
    </div>

    <!-- Form Pembayaran -->
    <div class="card mb-4">
    <div class="card-body">
        <h5>Input Pembayaran Angsuran</h5>

        {{-- Data dari backend --}}
        @php
            $sisa = $pinjaman->sisa_pokok - ($simpananpokok+$simpananwajib);
           
        @endphp

        <form action="{{ route('angsuran.store.pelunasan', $pinjaman->id_pinjaman) }}" method="POST">
            @csrf

            <div class="mb-3 col-md-3">
                <label>Total Pelunasan</label>
                <input type="hidden" id="simpanan" name="simpananpokok" class="form-control input-jumlah" value="{{$simpananpokok}}" > 
                <inpu type="hidden" id="simpananwajib" name="simpananwajib" class="form-control input-jumlah" value="{{$simpananwajib}}" >
                <input type="text" id="total_bayar" name="total_bayar" class="form-control input-jumlah" value="{{number_format($sisa,0)}}" required readonly>
            </div>


            <div class="mb-3 col-md-3">
                <label>Tanggal Bayar</label>
                <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="mb-3">
        <label>Metode Pembayaran</label>
        <select name="metode" class="form-control" required>
            <option value="">-- Pilih Metode --</option>
            <option value="ATM">ATM</option>
            <option value="Cash">Cash</option>
        </select>
    </div>
            
     
            <button type="submit" class="btn btn-info " >Bayar Pelunasan</button>
        </form>
    </div>
</div>




    <!-- History Pembayaran -->
    <
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

  
</script>
@endpush
</x-layout>
