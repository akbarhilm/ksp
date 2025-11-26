<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="cbook" menuParent="laporan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Tutup Buku"></x-navbars.navs.auth>
        <!-- End Navbar -->

<div class="container mt-4">

    <h3 class="mb-3">Tutup Buku</h3>

    {{-- FORM FILTER --}}
    <div class="card">
        <div class="card-body">
    <form method="POST" action="{{ route('tutupbuku.store') }}" class="row g-3 mb-4">
        @csrf
        <div class="row">
        <div class="col-md-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" readonly value="{{ date('Y-m-d') }}">
        </div>
        </div>
        <div class="row mt-3">

        <div class="col-md-2">
            <button class="btn btn-info" type="submit">Tutup Buku</button>
        </div>
        <div class="col-md-2">
           <a class="btn btn-dark " href="{{ url()->previous() }}">kembali</a>
        </div>
        </div>
    </form>
        </div></div>
</div>
    </main>
    {{-- ========================== TABEL SIMPANAN ========================== --}}
    
</x-layout>