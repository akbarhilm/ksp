<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="pinjaman" menuParent="loan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Pinjaman"></x-navbars.navs.auth>
        <!-- End Navbar -->
      <div class="container">
    <h2>Daftar Pinjaman</h2>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('pinjaman.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="id_nasabah" class="form-control" placeholder="ID Nasabah" value="{{ request('id_nasabah') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="nama" class="form-control" placeholder="Nama Nasabah" value="{{ request('nama') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status')=='aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="lunas" {{ request('status')=='lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('pinjaman.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Pinjaman -->
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID Pinjaman</th>
                        <th>ID Nasabah</th>
                        <th>Nama Nasabah</th>
                        <th>Total Pinjaman</th>
                        <th>Sisa Pokok</th>
                        <th>Sisa Bunga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pinjaman as $p)
                        <tr>
                            <td>{{ $p->id_pinjaman }}</td>
                            <td>{{ $p->id_nasabah }}</td>
                            <td>{{ $p->nasabah->nama ?? '-' }}</td>
                            <td>{{ number_format($p->total_pinjaman,0) }}</td>
                            <td>{{ number_format($p->sisa_pokok,0) }}</td>
                            <td>{{ number_format($p->sisa_bunga,0) }}</td>
                            <td>
                                <span class="badge {{ $p->status == 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('angsuran.index', $p->id_pinjaman) }}" class="btn btn-primary btn-sm">
                                    Bayar Angsuran
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data pinjaman</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
    </main>
    <x-plugins></x-plugins>
        @push('js')
<script>
   
            //fetchUsers(); // Load all users initially
           
            // Fetch users (AJAX GET)
            function fetchUsers(query = '') {
                $.ajax({
                    url: "{{ route('rekening.index') }}",
                    method: 'GET',
                    data: { param: query },
                    success: function(data) {
                        console,log(data)
                    }
                            });
                       
            }
            
            // Search as user types
           function cari() {
                let query =  $("#param").val()
                fetchUsers(query);
            }
        
  </script>
   @endpush
</x-layout>