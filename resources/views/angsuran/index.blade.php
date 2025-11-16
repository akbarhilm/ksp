<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="bukubesar" menuParent="laporan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Buku Besar"></x-navbars.navs.auth>
<div class="container">
    <h2>Pembayaran Angsuran Pinjaman #{{ $pinjaman->id_pinjaman }}</h2>

    <div class="card mb-4">
        <div class="card-body">
            <h5>Informasi Pinjaman</h5>
            <p><strong>Nasabah:</strong> {{ $pinjaman->id_nasabah }}</p>
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
            <form action="{{ route('angsuran.store', $pinjaman->id_pinjaman) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Pokok Dibayar</label>
                    <input type="number" name="pokok" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Bunga Dibayar</label>
                    <input type="number" name="bunga" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Tanggal Bayar</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="mb-3">
                    <label>Akun Kas</label>
                    <input type="number" name="id_akun_kas" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
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
                        <th>Pokok</th>
                        <th>Bunga</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $h)
                    <tr>
                        <td>{{ $h->tanggal_bayar }}</td>
                        <td>{{ number_format($h->pokok_dibayar,0) }}</td>
                        <td>{{ number_format($h->bunga_dibayar,0) }}</td>
                        <td>{{ $h->keterangan }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada pembayaran</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
    </main>
    <x-plugins></x-plugins>

</x-layout>
