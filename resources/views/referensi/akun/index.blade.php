<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="akun" menuParent="admin"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Referensi Akun"></x-navbars.navs.auth>
        <!-- End Navbar -->

<div class="container">

    {{-- Header & Tombol Tambah --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="m-0">Daftar Akun</h3>
        <a href="{{ route('akun.create') }}" class="btn btn-info">Tambah Akun</a>
    </div>

    {{-- Form Pencarian --}}
    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-3">
            <input type="text" 
                   name="kode_akun" 
                   value="{{ request('kode_akun') }}"
                   class="form-control" 
                   placeholder="Cari Kode Akun...">
        </div>

        <div class="col-md-4">
            <input type="text" 
                   name="nama_akun" 
                   value="{{ request('nama_akun') }}"
                   class="form-control" 
                   placeholder="Cari Nama Akun...">
        </div>

        <div class="col-md-3 d-flex">
            <button class="btn btn-primary me-2">Cari</button>
            <a href="{{ route('akun.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tabel Data --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="60">ID</th>
                        <th width="120">Kode</th>
                        <th>Nama Akun</th>
                        <th width="150">Tipe Akun</th>
                        <th width="120">Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($data as $d)
                        <tr>
                            <td>{{ $d->id_akun }}</td>
                            <td>{{ $d->kode_akun }}</td>
                            <td>{{ $d->nama_akun }}</td>
                            <td>{{ $d->tipe_akun }}</td>
                            <td>
                                <span class="badge bg-{{ $d->status == 'aktif' ? 'success' : 'secondary' }}">
                                    {{ $d->status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('akun.edit', $d->id_akun) }}" 
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                {{-- <form action="{{ route('akun.destroy', $d->id_akun) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Hapus akun ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form> --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $data->links() }}
            </div>

        </div>
    </div>

</div>

    </main>
        @push('js')
<script>
   
            
        
  </script>
   @endpush
</x-layout>