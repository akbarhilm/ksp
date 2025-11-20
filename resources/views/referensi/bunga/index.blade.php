<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="bunga" menuParent="admin"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Referensi Bunga"></x-navbars.navs.auth>
        <!-- End Navbar -->
     <div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="m-0">Data Bunga</h4>
        <a href="{{ route('bunga.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Bunga
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th width="50">#</th>
                        <th>Nama Bunga</th>
                        <th>Persentase (%)</th>
                        <th>Threshold</th>
                        <th>Persentase-2 (%)</th>
                        <th>Threshold-2</th>
                        <th width="160">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->nama_bunga }}</td>
                            <td>{{ $row->persentase }}%</td>
                            <td>@if($row->jenis_bunga == 'Simpanan'){{ number_format($row->threshold,'0',',','.') }}@else{{$row->threshold.' hari'}}@endif</td>
                             <td>{{ $row->persentase2 == 0? '-' : $row->persentase2.'%'  }}</td>
                            <td>@if($row->persentase2 != 0)@if($row->jenis_bunga == 'Simpanan'){{ number_format($row->threshold2,'0',',','.') }}@else{{$row->threshold2.' hari'}}@endif @else{{'-'}} @endif</td>
                            <td>
                                <a href="{{ route('bunga.edit', $row->id_bunga) }}" 
                                    class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('bunga.destroy', $row->id_bunga) }}" 
                                      method="POST" 
                                      class="d-inline">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Yakin hapus data?')" 
                                        class="btn btn-danger btn-sm">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $data->links() }}
    </div>
</div>
    </main>
        @push('js')
<script>
   
            
        
  </script>
   @endpush
</x-layout>