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
            <p><strong>Nasabah:</strong> {{ str_pad($pinjaman->id_nasabah , 5, '0', STR_PAD_LEFT).' / '.$pinjaman->nasabah->nama}}</p>
            <p><strong>Total Pinjaman:</strong> {{ number_format($pinjaman->total_pinjaman,0) }}</p>
            <p><strong>Sisa Pokok:</strong> {{ number_format($pinjaman->sisa_pokok,0) }}</p>
            <p><strong>Sisa Bunga:</strong> {{ number_format($pinjaman->sisa_bunga,0) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($pinjaman->status) }}</p>
            <p><strong>Tenor:</strong> {{ $pinjaman->pengajuan->tenor }}</p>

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
             $simpanan = $angsuran->simpanan;
        @endphp

        <form action="{{ route('angsuran.update', $angsuran->id_pembayaran) }}" method="POST">
            @csrf
            @method('PUT')
            <div class='row'>
            <div class="mb-3 col-md-6">
                <label>Total Bayar</label>
                <input type="text" id="total_bayar" name="total_bayar" class="form-control input-jumlah" required readonly >
            </div>

            <div class="mb-3 col-md-6">
                <label>Pokok Dibayar</label>
                <input type="text" name="bayar_pokok" id="pokok" class="form-control input-jumlah" onchange="adddenda()" value="{{ number_format($angsuran->bayar_pokok,0,',','.') }}"  required>
            </div>
            </div>
            <div class='row'>
            <div class="mb-3 col-md-6">
                <label>Bunga Dibayar</label>
                <input type="text" name="bayar_bunga" id="bunga" class="form-control input-jumlah" onchange="adddenda()" required value="{{ number_format($angsuran->bayar_bunga, 0,',','.')}}">
            </div>
            <div class="mb-3 col-md-6">
                <label>Denda</label>
                
                <input type="text" name="bayar_denda" id="denda" value={{number_format($angsuran->bayar_denda,0,',','.')}} class="form-control input-jumlah" onchange="adddenda()" >
            </div>
            </div>
            <div class='row'>
             <div class="mb-3 col-md-6">
                <label>Simpanan Wajib</label>
                <input type="text" name="simpanan" id="simpanan" class="form-control input-jumlah" onchange="adddenda()" required  value="{{ number_format($angsuran->simpanan,0,',','.') }}">
            </div>

            <div class="mb-3 col-md-6">
                <label>Tanggal Bayar</label>
                <input type="date" name="tanggal" class="form-control" value="{{ $angsuran->tanggal }}" required>
            </div>
            </div>
            <div class='row'>
            <div class="mb-3 col-md-6">
        <label>Metode Pembayaran</label>
        <select name="metode" class="form-control" required>
            <option value="">-- Pilih Metode --</option>
            <option value="ATM" {{ $angsuran->metode == 'ATM'?'selected':'' }}>ATM</option>
            <option value="Auto Debit" {{ $angsuran->metode == 'Auto Debit'?'selected':'' }}>Auto Debit</option>
            <option value="Cash" {{ $angsuran->metode == 'Cash'?'selected':'' }}>Cash</option>
        </select>
            </div>
        <div class="mb-3 col-md-6">
                <label>Cicilan Ke</label>
                <input type="number" name="cicilan_ke" class="form-control" required value="{{ $angsuran->cicilan_ke }}">
            </div>
    </div>
            
          <div class='d-flex justify-content-between'>
            @if(auth()->user()->role !== 'admin')
            <div class='gap-2'>
            <button type="submit" class="btn btn-info" >Simpan Perubahan Angsuran</button>
             <a class="btn btn-dark btn-link " href="{{ url()->previous() }}">kembali</a>
            </div>
           
            <a href='javascript:{}'  class="btn btn-danger" onclick="hapus()">Hapus Angsuran</a>
            @endif

            </div>
        </form>
    </div>
</div>
<form action="{{route('angsuran.destroy',$angsuran->id_pembayaran)}}" method='POST' id='formdelete'>
    @csrf
    @method('DELETE')
</form>



  

                </tbody>
            </table>
        </div>
    </div>
</div>
    </main>
            @push('js')
<script>
$(document).ready(function() {

    adddenda();
});
function hapus(id) {
    if (confirm('Hapus Angsuran?')) {
        document.getElementById('formdelete').submit();
    }
}
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
    let pokokBaru = toNumber($("#pokok").val());
    let bungaBaru = toNumber($("#bunga").val());

    // Jika kosong → pakai denda default dari server
   if (!dendaBaru) {
        dendaBaru = 0;
    }
     if (!simpanan) {
        simpanan = 0;
     }
     if(!pokokBaru){
        pokokBaru =0;
     }
     if(!bungaBaru){
        bungaBaru =0;
     }

    // Hitung total baru (TANPA memakai total lama)
    let totalBaru = pokokBaru + bungaBaru + dendaBaru + simpanan;

    // Set ulang total
    document.getElementById('total_bayar').value = angka(totalBaru);
}


     const jumlahPinjaman = {{ $jumlahPinjaman }};  
    const sukuBunga = {{ $sukuBunga }};  
    const tenor = {{ $tenor }}; 
    const simpanan = {{ $simpanan }}
    // Hitung bunga bulanan
    const bungaPerBulan = jumlahPinjaman * (sukuBunga / 100);

    // Hitung pokok per bulan
    const pokokPerBulan = jumlahPinjaman / tenor;
    // Total bayar bulanan
    const totalBulanan = pokokPerBulan + bungaPerBulan + simpanan;

    // Set otomatis ke form
    // document.getElementById('total_bayar').value = angka(Math.ceil(totalBulanan));
    // document.getElementById('pokok').value = angka(Math.ceil(pokokPerBulan));
    // document.getElementById('bunga').value = angka(Math.ceil(bungaPerBulan));
</script>
@endpush
</x-layout>
