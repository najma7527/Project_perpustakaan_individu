@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/siswa/dashboard-siswa.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')
<main class="main-content">

    <!-- HEADER -->
    <div class="header-card">
        <div class="header-left">
            <div class="header-icon">
                <i class="fa fa-user-graduate"></i>
            </div>
            <div>
                <h3>Hello, {{ auth()->user()->name ?? 'Siswa' }}! 👋</h3>
                <p>Selamat datang di perpustakaan digital</p>
            </div>
        </div>
    </div>

    <!-- NOTIFICATIONS -->
    @if(isset($notifications) && $notifications->count() > 0)
    <div class="notification-section">
        <div class="modern-card">
            <div class="card-header">
                <i class="fa fa-bell"></i>
                <h4>Notifikasi</h4>
            </div>
            <ul class="notification-list">
                @foreach($notifications as $notif)
                @php
                    $warna = 'info';
                    if(str_contains(strtolower($notif->message), 'terlambat')) $warna = 'danger';
                    elseif(str_contains(strtolower($notif->message), 'besok')) $warna = 'warning';
                @endphp
                <li class="notif-{{ $warna }}">
                    {{ $notif->message }}
                    <small>{{ $notif->created_at->diffForHumans() }}</small>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- STATS -->
    <div class="stats-modern">
        <div class="stat-box blue">
            <div class="stat-left">
                <p class="title">Sedang Dipinjam</p>
                <h2>{{ $totalDipinjam }}</h2>
            </div>
            <div class="stat-icon">
                <i class="fa fa-book-open"></i>
            </div>
        </div>
        <div class="stat-box orange">
            <div class="stat-left">
                <p class="title">Terlambat</p>
                <h2>{{ $totalTerlambat }}</h2>
            </div>
            <div class="stat-icon">
                <i class="fa fa-exclamation-triangle"></i>
            </div>
        </div>
        <div class="stat-box green">
            <div class="stat-left">
                <p class="title">Sudah Dikembalikan</p>
                <h2>{{ $totalPengembalian }}</h2>
            </div>
            <div class="stat-icon">
                <i class="fa fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-box red">
            <div class="stat-left">
                <p class="title">Buku Hilang</p>
                <h2>{{ $totalBukuHilang }}</h2>
            </div>
            <div class="stat-icon">
                <i class="fa fa-flag"></i>
            </div>
        </div>
    </div>

    <!-- ACTION CARDS (Hadir & Cetak Kartu) -->
    <div class="action-cards-grid">
        <div class="hadir-card">
            <div class="hadir-left">
                <div class="hadir-icon">
                    <i class="fa fa-fingerprint"></i>
                </div>
                <div class="hadir-text">
                    <h3>Kunjungan Perpustakaan</h3>
                    <p>Catat kehadiranmu hari ini</p>
                </div>
            </div>
            <button class="btn-hadir-action" id="btnHadir" {{ $kunjunganHariIni ? 'disabled' : '' }}>
                @if($kunjunganHariIni)
                    <i class="fa fa-check-circle"></i> Sudah Hadir
                @else
                    <i class="fa fa-calendar-check"></i> Hadir Sekarang
                @endif
            </button>
        </div>

        <div class="cetak-card">
            <div class="cetak-left">
                <div class="cetak-icon">
                    <i class="fa fa-id-card"></i>
                </div>
                <div class="cetak-text">
                    <h3>Kartu Anggota</h3>
                    <p>Unduh kartu anggota perpustakaan</p>
                </div>
            </div>
            <button type="button" class="btn-cetak-action" id="btnCetakKartu" onclick="downloadKartuSiswa()">
                <i class="fa fa-download"></i> Unduh Kartu
            </button>
        </div>
    </div>

    <!-- RIWAYAT PEMINJAMAN -->
    <div class="modern-card">
        <div class="card-header">
            <i class="fa fa-history"></i>
            <h4>Riwayat Peminjaman</h4>
        </div>
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Judul Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatPeminjaman as $trx)
                    <tr>
                        <td>{{ $trx->book->judul ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($trx->tanggal_peminjaman)->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($trx->tanggal_jatuh_tempo)->format('d-m-Y') }}</td>
                        <td>
                            <span class="badge 
                                @if($trx->status == 'dipinjam') warning
                                @elseif($trx->status == 'dikembalikan') success
                                @else danger
                                @endif
                            ">
                                {{ $trx->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center;">Belum ada riwayat peminjaman</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</main>

<script>
// Check-in functionality (unchanged)
const btnHadir = document.getElementById('btnHadir');
if(btnHadir) {
    btnHadir.addEventListener('click', async () => {
        try {
            const response = await fetch("{{ route('checkin') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            });
            const data = await response.json();
            if(response.ok) {
                btnHadir.innerHTML = '<i class="fa fa-check-circle"></i> Sudah Hadir';
                btnHadir.disabled = true;
                alert(data.message);
            } else {
                alert(data.message);
            }
        } catch (error) {
            alert("Terjadi error");
        }
    });
}

// Download kartu (unchanged)
function downloadKartuSiswa() {
    const btn = document.getElementById('btnCetakKartu');
    const originalHTML = btn ? btn.innerHTML : '';
    if (btn) {
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Mengunduh...';
        btn.disabled = true;
    }

    fetch("{{ route('kartu.download') }}")
        .then(response => {
            if (!response.ok) throw new Error('Gagal mengunduh kartu');
            const cd = response.headers.get('Content-Disposition');
            let filename = 'kartu-anggota.pdf';
            if (cd) {
                const match = cd.match(/filename[^;=\n]*=(['"]?)([^'"\n]*?)\1(;|$)/);
                if (match) filename = match[2];
            }
            return response.blob().then(blob => ({ blob, filename }));
        })
        .then(({ blob, filename }) => {
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(link.href);
        })
        .catch(error => {
            alert(error.message || 'Terjadi kesalahan saat mengunduh kartu.');
        })
        .finally(() => {
            if (btn) {
                btn.innerHTML = originalHTML;
                btn.disabled = false;
            }
        });
}
</script>
@endsection