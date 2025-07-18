@include('AdminLTE.head')
    <ul class="navbar-nav custom-green-gradient sidebar sidebar-dark accordion" id="accordionSidebar">


    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/home">
       
        <div class="sidebar-brand-text mx-3 mr-5"> Puskesmas Gandus</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="/home">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard {{ auth()->user()->name }}</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    {{-- Hanya tampil jika level adalah "admin" --}}
    @if(auth()->user()->level == 'admin')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('ibu.index') }}">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>SPK Ibu Hamil</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/laporan-klasifikasi">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Laporan Klasifikasi</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/user">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Data User</span></a>
    </li>
    @elseif(auth()->user()->level == 'kabid')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('ibu.index') }}">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>SPK Ibu Hamil</span></a>
    </li>
    @endif

    {{-- Tampil untuk level admin & kabid --}}
    @if(auth()->user()->level == 'admin' || auth()->user()->level == 'kabid')

    @endif

    <hr class="sidebar-divider">

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="btn btn-danger" style="margin-left: 70px; margin-top: 20px;">Logout</button>
    </form>
</ul>