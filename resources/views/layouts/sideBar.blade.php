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

            <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard.anggota') }}">
                    <i class="fa fa-home"></i> Dashboard
                </a>

            <li class="{{ Request::is('peminjaman*') ? 'active' : '' }}">
                <a href="/peminjaman">
                    <i class="fa fa-book-open"></i> Peminjaman
                </a>
            </li>

            <li class="{{ Request::is('pengembalian*') ? 'active' : '' }}">
                <a href="/pengembalian">
                    <i class="fa fa-rotate-left"></i> Pengembalian
                </a>
            </li>

            <li class="{{ Request::is('laporan_kehilangan*') ? 'active' : '' }}">
                <a href="/laporan_kehilangan">
                    <i class="fa fa-file-circle-exclamation"></i> Laporan Kehilangan
                </a>
            </li>

        @endif

    </ul>
</aside>
