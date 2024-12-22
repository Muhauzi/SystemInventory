<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        @if(auth()->user()->role == 'admin' ||  auth()->user()->role == 'pimpinan')
        <li class="nav-item">
            <a class="nav-link collapsed" href={{route('dashboard')}}>
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        @endif
        @if(auth()->user()->role == 'admin')
        <li class="nav-item">
            <a class="nav-link collapsed" href={{route('kelola_user.index')}}>
                <i class="bi bi-people-fill"></i><span>User</span>
            </a>
        </li><!-- End Components Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav1" data-bs-toggle="collapse" href="#" aria-expanded="{{ request()->is('inventaris*') || request()->is('kategori*') ? 'true' : 'false' }}">
                <i class="bi bi-box"></i><span>Inventaris</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav1" class="nav-content collapse {{ request()->is('inventaris*') || request()->is('kategori*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{route('inventaris.index')}}" class="{{ request()->is('inventaris') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Barang</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('kategori.index')}}" class="{{request()->is('kategori') ? 'active' : ''}}">
                        <i class="bi bi-circle"></i><span>Kategori Barang</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Tables Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav2" data-bs-toggle="collapse" href="#" aria-expanded="{{ request()->is('peminjaman*') ? 'true' : 'false' }}">
                <i class="bi bi-arrow-left-right"></i><span>Transaksi</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav2" class="nav-content collapse {{ request()->is('peminjaman*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-link collapsed {{ request()->is('peminjaman') ? 'active' : '' }}" href={{route('peminjaman.index')}}>
                        <i class="bi bi-circle"></i><span>Peminjaman</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link collapsed {{ request()->is('peminjaman/pengembalian') ? 'active' : '' }}" href={{route('peminjaman.pengembalian')}}>
                        <i class="bi bi-circle"></i><span>Pengembalian</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link collapsed {{ request()->is('peminjaman/listPerizinan') ? 'active' : '' }}" href="{{route('peminjaman.listPerizinan')}}">
                        <i class="bi bi-circle"></i><span>Izin Peminjaman</span>
                    </a>
                </li>       
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav21" data-bs-toggle="collapse" href="#" aria-expanded="{{ request()->is('peminjaman/laporan*') || request()->is('kerusakan*') ? 'true' : 'false' }}">
                <i class="bi bi-file-text"></i><span>Laporan</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav21" class="nav-content collapse {{ request()->is('peminjaman/laporan*') || request()->is('kerusakan*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-link collapsed {{ request()->is('peminjaman/laporan') ? 'active' : '' }}" href={{route('peminjaman.laporan')}}>
                        <i class="bi bi-circle"></i><span>Laporan Transaksi</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link collapsed {{ request()->is('kerusakan') ? 'active' : '' }}" href={{route('laporan_kerusakan.index')}}>
                        <i class="bi bi-circle"></i><span>Laporan Kerusakan</span>
                    </a>
                </li>
            </ul>
        </li>
        @endif
        @if (auth()->user()->role == 'pimpinan')
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav3" data-bs-toggle="collapse" href="#" aria-expanded="{{ request()->is('pimpinan.laporan_transaksi*') || request()->is('pimpinan.laporan_kerusakan*') ? 'true' : 'false' }}">
                <i class="bi bi-file-text"></i><span>Laporan</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav3" class="nav-content collapse {{ request()->is('pimpinan/laporan_transaksi*') || request()->is('pimpinan/laporan_kerusakan*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-link collapsed {{ request()->is('pimpinan/laporan_transaksi') ? 'active' : '' }}" href={{route('pimpinan.laporan_transaksi')}}>
                        <i class="bi bi-circle"></i><span>Laporan Transaksi</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link collapsed {{ request()->is('pimpinan/laporan_kerusakan') ? 'active' : '' }}" href={{route('pimpinan.laporan_kerusakan')}}>
                        <i class="bi bi-circle"></i><span>Laporan Kerusakan</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed {{ request()->is('izin_peminjaman') ? 'active' : '' }}" href="{{route('pimpinan.izin_peminjaman')}}">
                <i class="bi bi-clipboard-check"></i><span>Konfirmasi Peminjaman</span>
            </a>    
        </li>   

        @endif


        @if(auth()->user()->role == 'user')
        <li class="nav-heading">General</li>
        <li>
            <a class="nav-link collapsed {{ request()->is('user/riwayat_peminjaman') ? 'active' : '' }}" href="{{ route('user.riwayat_peminjaman') }}">
                <i class="bi bi-clock-history"></i><span>Riwayat Transaksi</span>
            </a>
        </li>

        <li>
            <a class="nav-link collapsed {{ request()->is('user/TagihanKerusakan') ? 'active' : '' }}" href="{{ route('user.TagihanKerusakan') }}">
                <i class="bi bi-cash-coin"></i><span>Tagihan Kerusakan</span>
            </a>
        </li>
        @endif
    </ul>

</aside>
