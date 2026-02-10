<aside class="sidebar">
    <div class="logo">
        <img src="{{ asset('img/logo.png') }}">
    </div>

    <ul class="menu">

        {{-- ================= ADMIN ================= --}}
        @if(Auth::check() && Auth::user()->role === 'admin')

            <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard.admin') }}">
                    <i class="fa fa-home"></i> Dashboard
                </a>
            </li>

            <li class="{{ Request::is('kelola_data_buku*') ? 'active' : '' }}">
                <a href="{{ route('books.index') }}">
                    <i class="fa fa-book"></i> Kelola Data Buku
                </a>
            </li>

            <li class="{{ Request::is('kelola_anggota*') ? 'active' : '' }}">
                <a href="{{ route('admin.anggota.index', ['tab' => 'verifikasi']) }}">
                    <i class="fa fa-users"></i> Kelola Anggota
                </a>
            </li>

            <li class="{{ Request::is('transaksi*') ? 'active' : '' }}">
                <a href="{{ route('transactions.index') }}">
                    <i class="fa fa-right-left"></i> Transaksi
                </a>
            </li>

            <li class="{{ Request::is('daftar_pengunjung*') ? 'active' : '' }}">
                <a href="{{ route('visits.index') }}">
                    <i class="fa fa-list"></i> Daftar Pengunjung
                </a>
            </li>

            <li class="{{ Request::is('laporan_data_kehilangan*') ? 'active' : '' }}">
                <a href="{{ route('reports.index') }}">
                    <i class="fa fa-file"></i> Laporan Kehilangan
                </a>
            </li>

        {{-- ================= ANGGOTA ================= --}}
        @elseif(Auth::check() && Auth::user()->role === 'anggota')

            <li class="{{ Request::is('dashboard-siswa*') || Request::routeIs('dashboard.anggota') ? 'active' : '' }}">
                <a href="{{ route('dashboard.anggota') }}">
                    <i class="fa fa-home"></i> Dashboard
                </a>
            </li>

            <li class="{{ Request::routeIs('books.browse') ? 'active' : '' }}">
                <a href="{{ route('books.browse') }}">
                    <i class="fa fa-book"></i> Pinjam Buku
                </a>
            </li>

            <li class="{{ Request::routeIs('transactions.mine') ? 'active' : '' }}">
                <a href="{{ route('transactions.mine') }}">
                    <i class="fa fa-book-open"></i> kembali Buku
                </a>
            </li>

            <li class="{{ Request::routeIs('laporan-kehilangan.index') ? 'active' : '' }}">
                <a href="{{ route('laporan-kehilangan.index') }}">
                    <i class="fa fa-file-circle-exclamation"></i> Laporan Kehilangan
                </a>
            </li>


        @endif

    </ul>
</aside>
