<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="bunga" menuParent="admin"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Referensi Bunga"></x-navbars.navs.auth>
        <!-- End Navbar -->
    <div class="container">

    <div class="card shadow-sm">
        <div class="card-header bg-info ">
            <h5 class="m-0 text-white">Tambah Data Bunga</h5>
        </div>

        <div class="card-body">

            <form action="{{ route('bunga.store') }}" method="POST">
                @csrf
<div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Bunga</label>
                    <input type="text" 
                           name="nama_bunga" 
                           class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Jenis Bunga</label>
                    <select class="form-control"  name="jenis_bunga" required>
                        <option value="">--Pilih Jenis--</option>
                        <option value="Simpanan">Tabungan</option>
                        <option value="Deposito">Deposito</option>

                        <option value="Denda">Denda</option>
                    </select>
                </div>
            </div>
<div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Persentase-1 (%)</label>
                    <input type="number" 
                           name="persentase" 
                           step="0.01" 
                           class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Threshold-1</label>
                   <input type="number" 
                           name="threshold" 
                           step="0.01" 
                           class="form-control" required
                           placeholder="nilai Rp atau Hari">
                </div>
</div>

                <button class="btn btn-success">Simpan</button>
                <a href="{{ route('bunga.index') }}" class="btn btn-secondary">Kembali</a>
            </form>

        </div>
    </div>

</div>
    </main>
        @push('js')
<script>
   
            
        
  </script>
   @endpush
</x-layout>