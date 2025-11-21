<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="bunga" menuParent="admin"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Referensi Bunga"></x-navbars.navs.auth>
        <!-- End Navbar -->
    <div class="container">

    <div class="card shadow-sm">
        <div class="card-header bg-warning">
            <h5 class="m-0 text-white">Edit Data Bunga</h5>
        </div>

        <div class="card-body">

            <form action="{{ route('bunga.update', $bunga->id_bunga) }}" method="POST">
                @csrf @method('PUT')

                <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Bunga</label>
                    <input type="text" 
                           name="nama_bunga" 
                           class="form-control" required
                           value="{{$bunga->nama_bunga}}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Jenis Bunga</label>
                    <select class="form-control"  name="jenis_bunga" required>
                        <option value="">--Pilih Jenis--</option>
                        <option value="Simpanan" {{$bunga->jenis_bunga == 'Simpanan'?'selected':''}}>Simpanan</option>
                        <option value="Denda"  {{$bunga->jenis_bunga == 'Denda'?'selected':''}}>Denda</option>
                    </select>
                </div>
            </div>
<div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Persentase-1 (%)</label>
                    <input type="number" 
                           name="persentase" 
                           step="0.01" 
                           class="form-control" required
                           value="{{$bunga->persentase}}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Threshold-1</label>
                   <input type="number" 
                           name="threshold" 
                           step="0.01" 
                           class="form-control" required
                           value="{{$bunga->threshold}}">
                </div>
</div>
<div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Persentase-2 (%)</label>
                    <input type="number" 
                           name="persentase2" 
                           step="0.01" 
                           class="form-control" required
                           value="{{$bunga->persentase2}}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Threshold-2</label>
                   <input type="number" 
                           name="threshold2" 
                           step="0.01" 
                           class="form-control" required
                           value="{{$bunga->threshold2}}">
                </div>
</div>

                <button class="btn btn-warning text-white">Update</button>
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