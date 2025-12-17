<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="history" menuParent="loan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="History Pinjaman"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="card mb-4">
                <div class="card-body">
                  <h5>Informasi Nasabah</h5>
            <p><strong>Nasabah:</strong> {{ str_pad($nasabah->id_nasabah , 5, '0', STR_PAD_LEFT).' / '.$nasabah->nama}}</p>
            <p><strong>No KTP:</strong> {{$nasabah->nik }}</p>
            <p><strong>Tanggal Lahir:</strong> {{$nasabah->tgl_lahir }}</p>
            <p><strong>No Telp:</strong> {{ $nasabah->no_telp }}</p>
                </div>
            </div>
    <div class="row">
        <div class="col-12">

            <div class="card shadow-sm mb-4">
                <div class="card-title m-3">
                    <h6>Pengajuan Belum Cair</h6>
                </div>
                <div class="card-body overflow-auto">
                   
      <table id="nasabahTable"  class="table table-striped table-hover align-middle text-sm" width="100%">
    <thead class="table-dark">
        <tr>
            <th>Tanggal</th>
            <th>Bunga</th>
            <th>Tenor</th>
            <th>Nilai Pengajuan</th>
            <th>Jenis</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pengajuan as $p )
        <tr>
            <td>{{$p->tanggal_pengajuan}}</td>
            <td>{{$p->bunga}}</td>
            <td>{{$p->tenor}}</td>
            <td>{{number_format($p->jumlah_pengajuan,0,',','.')}}</td>
            <td>{{$p->jenis}}</td>
            <td>{{$p->status}}</td>
            <td>a</td>
        </tr>
        @endforeach
    </tbody>
</table>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
         <div class="card-body">
            <h6>Pinjaman</h6>
         </div>
         <table id="nasabahTable"  class="table table-striped table-hover align-middle text-sm" width="100%">
    <thead class="table-dark">
        <tr>
            <th>Tanggal Cair</th>
            
            <th>Nilai Pinjaman</th>
            <th>Sisa Pokok</th>
            <th>Sisa Bunga</th>

            <th>Status</th>
            <th class='text-center'>Cetak/Lihat</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pinjaman as $p )
        @php
            $id = $p->id_pinjaman
        @endphp
        <tr>
            <td class='text-center'>{{$p->pengajuan->tanggal_pencairan}}</td>
            <td class='text-end'>{{number_format($p->total_pinjaman,0,',','.')}}</td>

            <td class='text-end'>{{number_format($p->sisa_pokok,0,',','.')}}</td>

            <td class='text-end'>{{number_format($p->sisa_bunga,0,',','.')}}</td>

            <td class='text-center'>{{$p->status}}</td>

            <td class='d-grid'>
                <a href="{{route('pengajuan.detail.pencairan', $p->id_pengajuan)}}" class='btn btn-sm btn-info'>Pengajuan</a>
                <a href="{{route('cetak.perjanjian',$p->id_pengajuan)}}" class='btn btn-sm btn-secondary'>Perjanjian</a>
                <a href="{{route('cetak.jaminan',$p->id_pengajuan)}}" class='btn btn-sm btn-info'>Jaminan</a>
                <a href="{{route('cetak.pencairan',$id)}}" class='btn btn-sm btn-secondary'>Pencairan</a>
                <a href="{{route('cetak.angsuran',$id)}}" class='btn btn-sm btn-info'>Angsuran</a>

            
            </td>

        </tr>
        @endforeach
    </tbody>
         </table>
         

    </div>
        </div>
        
    </main>

  @push('js')
<script>
 

</script>
@endpush
</x-layout>


