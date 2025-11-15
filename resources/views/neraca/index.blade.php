<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="neraca" menuParent="laporan"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Laporan Neraca"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <h3>Aktiva</h3>
<table>
@foreach($ledger as $row)
    @if($row->akun->tipe_akun == 'Aset')
    <tr>
        <td>{{ $row->akun->nama_akun }}</td>
        <td class="text-end">{{ number_format($row->saldo,0) }}</td>
    </tr>
    @endif
@endforeach
<tr>
    <td><b>Total Aktiva / Aset<b></td>
    <td class ="text-end"><b>{{number_format($total['aset'],0)}}<b></td>
</tr>
</table>
<br>
<h3>Kewajiban</h3>
<table>
@foreach($ledger as $row)
    @if($row->akun->tipe_akun == 'Kewajiban')
    <tr>
        <td>{{ $row->akun->nama_akun }}</td>
        <td class="text-end">{{ number_format($row->saldo,0) }}</td>
    </tr>
    @endif
@endforeach
<tr>
    <td class=""><b>Total Kewajiban<b></td>
    <td class ="text-end"><b>{{number_format($total['wajib'],0)}}<b></td>
</tr>
</table>

                    </div>
                </div>
            </div>
            {{-- <x-footers.auth></x-footers.auth> --}}
        </div>
    </main>
    <x-plugins></x-plugins>

</x-layout>
