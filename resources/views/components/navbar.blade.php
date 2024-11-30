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
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link " data-bs-target="#tables-nav2" data-bs-toggle="collapse" href="#">
                <i class="bi bi-book"></i><span>Laporan</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav2" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
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

        <li class="nav-heading">Pages</li>

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

        <li class="nav-item">
            <a class="nav-link collapsed" href="pages-faq.html">
                <i class="bi bi-question-circle"></i>
                <span>F.A.Q</span>
            </a>
        </li><!-- End F.A.Q Page Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" href="pages-contact.html">
                <i class="bi bi-envelope"></i>
                <span>Contact</span>
            </a>
        </li><!-- End Contact Page Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" href="pages-register.html">
                <i class="bi bi-card-list"></i>
                <span>Register</span>
            </a>
        </li><!-- End Register Page Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" href="pages-login.html">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Login</span>
            </a>
        </li><!-- End Login Page Nav -->

        <!-- End Blank Page Nav -->

    </ul>

</aside>