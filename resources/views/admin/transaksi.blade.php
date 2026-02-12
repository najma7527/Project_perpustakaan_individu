@extends('layouts.app')

@section('title', 'Transaksi')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/transaksi.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/card.css') }}">
@endpush
@section('content')

<!-- HEADER -->
<div class="header-card">
    <div class="header-left">
        <div class="header-icon">
            <i class="fa fa-user"></i>
        </div>
        <div>
            <h3>Transaksi</h3>
            <p>Pengembalian dan Peminjaman Buku</p>
        </div>
    </div>
    <img src="{{ asset('img/book.png') }}" class="header-img">
</div>

<!-- TAB -->
<div class="top-action">
    <div class="tabs">
        <a href="?mode=peminjaman"
           class="tab {{ ($mode ?? 'peminjaman') == 'peminjaman' ? 'active' : '' }}">
            Peminjaman
        </a>

        <a href="?mode=pengembalian"
           class="tab {{ ($mode ?? '') == 'pengembalian' ? 'active' : '' }}">
            Pengembalian
        </a>
    </div>
</div>

<!-- FILTER -->
<div class="filter">
    <div class="search">
        <i class="icon fa fa-search"></i>
        <input type="text" placeholder="Cari Sesuatu...">
    </div>

    <div class="date">
        <i class="icon fa fa-calendar"></i>
        <input type="date">
    </div>

    <button class="btn-filter">
        <i class="fa fa-sliders"></i>
    </button>

    @auth
    <a href="" class="btn-print">
        <i class="fa-solid fa-print"></i>
        Cetak Laporan
    </a>
    @endauth
</div>

{{-- ================= PEMINJAMAN ================= --}}
@if(($mode ?? 'peminjaman') == 'peminjaman')

<div class="table-wrapper">
<table>
<thead>
<tr>
    <th>No</th>
    <th>Nama Anggota</th>
    <th>Judul Buku</th>
    <th>Kelas</th>
    <th>Tgl Pinjam</th>
    <th>Jatuh Tempo</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>
@forelse($transactions as $trx)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $trx->user->name ?? '-' }}</td>
    <td>{{ $trx->book->judul ?? '-' }}</td>
    <td>{{ $trx->user->kelas ?? '-' }}</td>
    <td>{{ optional($trx->tanggal_peminjaman)->format('d/m/Y') }}</td>
    <td>{{ optional($trx->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
    <td>
@if($trx->status == 'belum_dikembalikan')
    <span class="status danger">Belum Dikembalikan</span>

@elseif($trx->status == 'buku_hilang')
    <span class="status warning">Buku Hilang</span>

@elseif($trx->status == 'sudah_dikembalikan')
    <span class="status success">Sudah Dikembalikan</span>
@endif
</td>
<td class="aksi">
@if($trx->status == 'buku_hilang')
    <span>-</span>
@elseif($trx->status == 'belum_dikembalikan')
    <span class="btn-filter btn-nota" data-nama="{{ $trx->user->name }}"><i class="fa-solid fa-print"></i></span>
@endif
</td>
</tr>
@empty
<tr>
    <td colspan="7" style="text-align:center">Tidak ada data</td>
</tr>
@endforelse
</tbody>
</table>
</div>
@endif

{{-- ================= PENGEMBALIAN ================= --}}
@if(($mode ?? '') == 'pengembalian')

<div class="table-wrapper">
<table>
<thead>
<tr>
    <th>No</th>
    <th>Nama Anggota</th>
    <th>Judul Buku</th>
    <th>Kelas</th>
    <th>Jatuh Tempo</th>
    <th>Tanggal Pengembalian</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>
@forelse($transactions as $trx)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $trx->user->name ?? '-' }}</td>
    <td>{{ $trx->book->judul ?? '-' }}</td>
    <td>{{ $trx->user->kelas ?? '-' }}</td>
    <td>{{ optional($trx->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
    <td>{{ optional($trx->tanggal_pengembalian)->format('d/m/Y') }}</td>
    <td class="aksi">
@if($trx->status == 'menunggu_konfirmasi')
    <span class="btn-green btn-approve" data-nama="{{ $trx->user->name }}">✔</span>
    <span class="btn-red btn-reject" data-nama="{{ $trx->user->name }}">✖</span>

@elseif($trx->status == 'sudah_dikembalikan')
    <span class="btn-filter btn-nota" data-nama="{{ $trx->user->name }}"><i class="fa-solid fa-print"></i></span>
@endif
</td>
</tr>
@empty
<tr>
    <td colspan="7" style="text-align:center">Tidak ada data</td>
</tr>
@endforelse
</tbody>
</table>
</div>
@endif
<script>
    // ambil data yang sudah disetujui
    const approved = JSON.parse(localStorage.getItem("approved_peminjaman")) || {};

    // === LOAD STATUS SAAT HALAMAN DIBUKA ===
    document.querySelectorAll(".btn-approve").forEach(btn => {
        const nama = btn.dataset.nama;
        const row = btn.closest("tr");
        const reject = row.querySelector(".btn-reject");

        if (approved[nama]) {
            row.style.background = "#ecfdf5";
            if (reject) reject.remove();

            btn.style.opacity = "0.6";
            btn.style.cursor = "default";
            btn.replaceWith(btn.cloneNode(true));
        }
    });

    // === KLIK SETUJUI ===
    document.querySelectorAll(".btn-approve").forEach(btn => {
        btn.addEventListener("click", function () {
            const nama = this.dataset.nama;
            const row = this.closest("tr");
            const reject = row.querySelector(".btn-reject");

            if (confirm("Setujui peminjaman?")) {
                approved[nama] = true;
                localStorage.setItem(
                    "approved_peminjaman",
                    JSON.stringify(approved)
                );

                row.style.background = "#ecfdf5";
                if (reject) reject.remove();

                this.style.opacity = "0.6";
                this.style.cursor = "default";
                this.replaceWith(this.cloneNode(true));
            }
        });
    });

    // === KLIK TOLAK ===
    document.querySelectorAll(".btn-reject").forEach(btn => {
        btn.addEventListener("click", function () {
            const nama = this.dataset.nama;

            if (confirm("Tolak peminjaman?")) {
                delete approved[nama];
                localStorage.setItem(
                    "approved_peminjaman",
                    JSON.stringify(approved)
                );

                this.closest("tr").remove();
            }
        });
    });
</script>
@endsection
