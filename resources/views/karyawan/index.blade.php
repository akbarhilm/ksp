<x-layout bodyClass="g-sidenav-show  bg-gray-200">

    <x-navbars.sidebar activePage="karyawan" menuParent="admin"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Managemen Karyawan"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="card my-4">
                <span class="text-lg">Konten karyawan </span>
</div>
            
            {{-- <x-footers.auth></x-footers.auth> --}}
        </div>
    </main>
    <x-plugins></x-plugins>

</x-layout>
