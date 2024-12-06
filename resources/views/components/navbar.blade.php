<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        @if(auth()->user()->role == 'admin')
        <li class="nav-item">
            <a class="nav-link collapsed" href={{route('dashboard')}}>
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" href={{route('kelola_user.index')}}>
                <i class="bi bi-people-fill"></i><span>User</span>
            </a>
        </li><!-- End Components Nav -->

        <li class="nav-item">
            <a class="nav-link " data-bs-target="#tables-nav1" data-bs-toggle="collapse" href="#">
                <i class="bi bi-box"></i><span>Inventaris</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav1" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{route('inventaris.index')}}" class="
                    {{ request()->is('inventaris') ? 'active' : '' }}
                    ">
                        <i class="bi bi-circle"></i><span>Data Barang</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('kategori.index')}}" class="{{request()->is('kategori') ? 'active' : ''}}">
                        <i class="bi bi-circle"></i><span>Kelola Kategori Barang</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Tables Nav -->

        <li class="nav-item">
            <a class="nav-link " data-bs-target="#tables-nav2" data-bs-toggle="collapse" href="#">
                <i class="bi bi-book"></i><span>Peminjaman</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav2" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-link collapsed {{ request()->is('peminjaman') ? 'active' : '' }}" href={{route('peminjaman.index')}}>
                        <i class="bi bi-circle"></i><span>Data Peminjaman</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link collapsed {{ request()->is('peminjaman/pengembalian') ? 'active' : '' }}" href={{route('peminjaman.pengembalian')}}>
                        <i class="bi bi-circle"></i><span>Data Pengembalian</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link collapsed {{ request()->is('listPerizinan') ? 'active' : '' }}" href="{{route('peminjaman.listPerizinan')}}">
                        <i class="bi bi-circle"></i><span>Izin Peminjaman</span>
                    </a>
                </li>       
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link " data-bs-target="#tables-nav21" data-bs-toggle="collapse" href="#">
                <i class="bi bi-file-text"></i><span>Laporan</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav21" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-link collapsed {{ request()->is('peminjaman') ? 'active' : '' }}" href={{route('peminjaman.index')}}>
                        <i class="bi bi-circle"></i><span>Laporan Peminjaman</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link collapsed {{ request()->is('peminjaman/pengembalian') ? 'active' : '' }}" href={{route('peminjaman.pengembalian')}}>
                        <i class="bi bi-circle"></i><span>Laporan Pengembalian</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link collapsed {{ request()->is('laporan_kerusakan') ? 'active' : '' }}" href={{route('laporan_kerusakan.index')}}>
                        <i class="bi bi-circle"></i><span>Laporan Kerusakan</span>
                    </a>
                </li>
            </ul>
        </li>
        @endif
        @if (auth()->user()->role == 'pimpinan')
        <li class="nav-item">
            <a class="nav-link" data-bs-target="#tables-nav3" data-bs-toggle="collapse" href="#">
                <i class="bi bi-file-text"></i><span>Laporan</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav3" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-link collapsed {{ request()->is('peminjaman') ? 'active' : '' }}" href={{route('peminjaman.index')}}>
                        <i class="bi bi-circle"></i><span>Laporan Peminjaman</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link collapsed {{ request()->is('peminjaman/pengembalian') ? 'active' : '' }}" href={{route('peminjaman.pengembalian')}}>
                        <i class="bi bi-circle"></i><span>Laporan Pengembalian</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link collapsed {{ request()->is('laporan_kerusakan') ? 'active' : '' }}" href={{route('laporan_kerusakan.index')}}>
                        <i class="bi bi-circle"></i><span>Laporan Kerusakan</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed {{ request()->is('izin_peminjaman') ? 'active' : '' }}" href="{{route('pimpinan.izin_peminjaman')}}">
                <i class="bi bi-clipboard-check"></i><span>Izin Peminjaman</span>
            </a>    
        </li>   

        @endif



        <li class="nav-heading">General</li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('user.profile') }}">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        </li><!-- End Profile Page Nav -->

        <li>
            <a class="nav-link collapsed {{ request()->is('user/riwayat_peminjaman') ? 'active' : '' }}" href="{{ route('user.riwayat_peminjaman') }}">
                <i class="bi bi-clock-history"></i><span>Riwayat Peminjaman</span>
            </a>
        </li>

        <li>
            <a class="nav-link collapsed {{ request()->is('user/riwayat_pengembalian') ? 'active' : '' }}" href="">
                <i class="bi bi-circle"></i><span>Riwayat Pengembalian</span>
            </a>
        </li>
    </ul>

</aside>