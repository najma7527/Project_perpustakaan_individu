<aside class="sidebar">
    <div class="logo">
        <img src="{{ asset('img/logo_aksapusta1.png') }}" alt="Logo">
    </div>

    <ul class="menu">

        {{-- ================= ADMIN ================= --}}
        @if(Auth::check() && Auth::user()->role === 'admin')

            <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard.admin') }}">
                    <i class="fa fa-home"></i> <span>Dashboard</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/books*') ? 'active' : '' }}">
                <a href="{{ route('books.index') }}">
                    <i class="fa fa-book"></i> <span>Kelola Data Buku</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/anggota*') ? 'active' : '' }}">
                <a href="{{ route('admin.anggota.index', ['tab' => 'verifikasi']) }}">
                    <i class="fa fa-users"></i> <span>Kelola Anggota</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/transactions*') ? 'active' : '' }}">
                <a href="{{ route('transactions.index') }}">
                    <i class="fa fa-right-left"></i> <span>Transaksi</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/visits*') ? 'active' : '' }}">
                <a href="{{ route('visits.index') }}">
                    <i class="fa fa-list"></i> <span>Daftar Pengunjung</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/reports*') ? 'active' : '' }}">
                <a href="{{ route('reports.index') }}">
                    <i class="fa fa-file"></i> <span>Laporan Kehilangan</span>
                </a>
            </li>

        {{-- ================= ANGGOTA ================= --}}
        @elseif(Auth::check() && Auth::user()->role === 'anggota')

            <li class="{{ Request::is('dashboard-siswa*') || Request::is('dashboard-anggota*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.anggota') }}">
                    <i class="fa fa-home"></i> <span>Dashboard</span>
                </a>
            </li>

            <li class="{{ Request::is('pinjam-buku*') ? 'active' : '' }}">
                <a href="{{ route('books.browse') }}">
                    <i class="fa fa-book"></i> <span>Pinjam Buku</span>
                </a>
            </li>

            <li class="{{ Request::is('my-transactions*') || Request::is('pengembalian-buku*') ? 'active' : '' }}">
                <a href="{{ route('transactions.mine') }}">
                    <i class="fa fa-book-open"></i> <span>Kembali Buku</span>
                </a>
            </li>

            <li class="{{ Request::is('laporan-kehilangan*') || Request::is('laporan_kehilangan*') ? 'active' : '' }}">
                <a href="{{ route('laporan-kehilangan.index') }}">
                    <i class="fa fa-file-circle-exclamation"></i> <span>Laporan Kehilangan</span>
                </a>
            </li>

        @endif

    </ul>
</aside>