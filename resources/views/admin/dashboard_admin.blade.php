@extends('layouts.app')

@section('title', 'Dashboard Admin')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin/dashboard_admin.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')
<div class="main-content">
    <!-- HEADER -->
    <div class="header-card">
        <div class="header-left">
            <div class="header-icon">
                <i class="fa fa-user-shield"></i>
            </div>
            <div>
                <h3>Hello, {{ Auth::user()->name ?? 'Admin' }}! 👋</h3>
                <p>Selamat datang di panel admin perpustakaan</p>
            </div>
        </div>
    </div>

    <!-- STATS -->
    <div class="stats-modern">
        <div class="stat-box blue">
            <div class="stat-left">
                <p class="title">Buku</p>
                <h2>{{ number_format($totalBook) }}</h2>
                <span class="up"><i class="fa fa-arrow-up"></i> Data buku</span>
            </div>
            <div class="stat-icon">
                <i class="fa fa-book"></i>
            </div>
        </div>

        <div class="stat-box green">
            <div class="stat-left">
                <p class="title">Dipinjam</p>
                <h2>{{ number_format($totalBorrow) }}</h2>
                <span class="up"><i class="fa fa-arrow-up"></i> Total dipinjam</span>
            </div>
            <div class="stat-icon">
                <i class="fa fa-right-left"></i>
            </div>
        </div>

        <div class="stat-box purple">
            <div class="stat-left">
                <p class="title">Dikembalikan</p>
                <h2>{{ number_format($totalReturn) }}</h2>
                <span class="down"><i class="fa fa-arrow-down"></i> Total dikembalikan</span>
            </div>
            <div class="stat-icon">
                <i class="fa fa-rotate-left"></i>
            </div>
        </div>

        <div class="stat-box orange">
            <div class="stat-left">
                <p class="title">Pengunjung</p>
                <h2>{{ number_format($totalVisit) }}</h2>
                <span class="down"><i class="fa fa-arrow-down"></i> Total pengunjung</span>
            </div>
            <div class="stat-icon">
                <i class="fa fa-users"></i>
            </div>
        </div>

        <div class="stat-box red">
            <div class="stat-left">
                <p class="title">Terlambat</p>
                <h2>{{ number_format($totalTerlambat) }}</h2>
                <span class="down"><i class="fa fa-arrow-down"></i> Transaksi terlambat</span>
            </div>
            <div class="stat-icon">
                <i class="fa fa-clock"></i>
            </div>
        </div>
    </div>

    <!-- PENGUNJUNG HARI INI -->
    <div class="modern-card">
        <div class="card-header center">
            <i class="fa fa-user-check"></i>
            <h4>Pengunjung Hari Ini</h4>
        </div>
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Nama Pengunjung</th>
                        <th>Transaksi</th>
                        <th>Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($todayVisit as $visit)
                    <tr>
                        <td>{{ $visit->user->name ?? '-' }}</td>
                        <td>{{ $visit->transaction->jenis_transaksi ?? '-' }}</td>
                        <td>{{ $visit->user->kelas ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">Belum ada pengunjung hari ini</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- BOTTOM SECTION (Laporan Kehilangan & Total Buku Hilang) -->
    <div class="bottom-section">
        <!-- LAPORAN KEHILANGAN BUKU -->
        <div class="modern-card">
            <div class="card-header center">
                <i class="fa fa-book-open"></i>
                <h4>Laporan Kehilangan Buku</h4>
            </div>
            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Nama Anggota</th>
                            <th>Judul Buku</th>
                            <th>Kelas</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Mengganti</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestReport as $report)
                        <tr>
                            <td>{{ $report->transaction->user->name ?? '-' }}</td>
                            <td>{{ $report->transaction->book->judul ?? '-' }}</td>
                            <td>{{ $report->transaction->user->kelas ?? '-' }}</td>
                            <td>{{ optional($report->transaction->tanggal_peminjaman)->format('d/m/Y') }}</td>
                            <td>{{ $report->tanggal_ganti ? \Carbon\Carbon::parse($report->tanggal_ganti)->format('d/m/Y') : '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data kehilangan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TOTAL BUKU HILANG BULAN INI -->
        <div class="lost-card">
            <div class="lost-header">
                <h4>Total Buku Hilang saat ini</h4>
                <div class="lost-icon">
                    <i class="fa-solid fa-book-open"></i>
                </div>
            </div>
            <div class="lost-body">
                <div class="lost-item danger">
                    <i class="fa fa-triangle-exclamation"></i>
                    <span>{{ $totalLostBooks }} Buku Hilang</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection