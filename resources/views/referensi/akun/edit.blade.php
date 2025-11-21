<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="akun" menuParent="admin"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Refrensi Akun"></x-navbars.navs.auth>
        <!-- End Navbar -->
   
<div class="container">

    <h3 class="mb-3">Edit Akun</h3>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('akun.update', $akun->id_akun) }}">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label>Kode Akun</label>
                    <input type="text" name="kode_akun" class="form-control" value="{{ $akun->kode_akun }}">
                </div>

                <div class="mb-3">
                    <label>Nama Akun</label>
                    <input type="text" name="nama_akun" class="form-control" value="{{ $akun->nama_akun }}">
                </div>

                <div class="mb-3">
                    <label>Tipe Akun</label>
                    <select name="tipe_akun" class="form-select">
                        @foreach(['Aset','Kewajiban','Modal','Pendapatan','Beban'] as $t)
                            <option {{ $akun->tipe_akun == $t ? 'selected':'' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="aktif" {{ $akun->status == 'aktif' ? 'selected':'' }}>Aktif</option>
                        <option value="nonaktif" {{ $akun->status == 'nonaktif' ? 'selected':'' }}>Nonaktif</option>
                    </select>
                </div>

                <button class="btn btn-primary">Update</button>
                <a href="{{ route('akun.index') }}" class="btn btn-secondary">Kembali</a>

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