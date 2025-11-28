<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="akun" menuParent="admin"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Referensi Akun"></x-navbars.navs.auth>
        <!-- End Navbar -->

<div class="container">

    <h3 class="mb-3">Tambah Akun</h3>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('akun.store') }}">
                @csrf

                <div class="mb-3">
                    <label>Kode Akun</label>
                    <input type="text" name="kode_akun" class="form-control" value="{{ old('kode_akun') }}" required>
                     @error('kode_akun')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                </div>

                <div class="mb-3">
                    <label>Nama Akun</label>
                    <input type="text" name="nama_akun" class="form-control" value="{{ old('nama_akun') }}" required>
                     @error('nama_akun')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                </div>

                <div class="mb-3">
                    <label>Tipe Akun</label>
                    <select name="tipe_akun" class="form-select">
                        <option value="Aset">Aset</option>
                        <option value="Kewajiban">Kewajiban</option>
                        <option value="Modal">Modal</option>
                        <option value="Pendapatan">Pendapatan</option>
                        <option value="Beban">Beban</option>
                    </select>
                     @error('tipe_akun')
                                            <p class='text-danger inputerror'>{{ $message }} </p>
                                        @enderror
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>

                <button class="btn btn-primary">Simpan</button>
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