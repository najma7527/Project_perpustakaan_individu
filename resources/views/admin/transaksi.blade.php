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
    <a href="{{ route('cetak.filter-transaksi') }}" class="btn-print">
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
    <td>{{ $transactions->firstItem() + $loop->index }}</td>
    <td>{{ $trx->user->name ?? '-' }}</td>
    <td>{{ $trx->book->judul ?? '-' }}</td>
    <td>{{ $trx->user->kelas ?? '-' }}</td>
    <td>{{ optional($trx->tanggal_peminjaman)->format('d/m/Y') }}</td>
    <td>{{ optional($trx->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
    <td>
    @if($trx->status == 'belum_dikembalikan')
        <span class="status blue">Belum Dikembalikan</span>
    @elseif($trx->status == 'buku_hilang')
        <span class="status danger">Buku Hilang</span>
    @elseif($trx->status == 'terlambat')
        <span class="status warning">Terlambat</span>
    @endif
    </td>
<td class="aksi">
@if($trx->status == 'belum_dikembalikan')
<span class="btn-filter btn-nota"
      onclick="window.open('{{ route('cetak.nota', [$trx->id, 'peminjaman']) }}', '_blank')">
    <i class="fa-solid fa-print"></i>
</span>
@elseif(in_array($trx->status, ['terlambat', 'buku_hilang']))
    <span>-</span>
@endif
</td>
</tr>
@empty
<tr>
    <td colspan="8" style="text-align:center">Tidak ada data</td>
</tr>
@endforelse
</tbody>
                <tfoot>
                    <tr>
                        <td colspan="8">
                            @include('components.pagination', ['paginator' => $transactions])
                        </td>
                    </tr>
                </tfoot>
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
    <th>Status</th>
    <th>Tgl Kembali</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>
@forelse($transactions as $trx)
<tr>
    <td>{{ $transactions->firstItem() + $loop->index }}</td>
    <td>{{ $trx->user->name ?? '-' }}</td>
    <td>{{ $trx->book->judul ?? '-' }}</td>
    <td>{{ $trx->user->kelas ?? '-' }}</td>
    <td>{{ optional($trx->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
    <td>
        @if($trx->status == 'menunggu_konfirmasi')
            <span class="status warning">Menunggu Persetujuan</span>
        @elseif($trx->status == 'sudah_dikembalikan')
            <span class="status success">Sudah Dikembalikan</span>
        @endif
    </td>
    <td>{{ $trx->tanggal_pengembalian ? $trx->tanggal_pengembalian->format('d/m/Y') : '-' }}</td>
    <td class="aksi" style="display: flex; gap: 5px; justify-content: center;">
@if($trx->status == 'menunggu_konfirmasi')
    <form action="{{ route('transactions.terimaPengembalian', $trx->id) }}" method="POST" onsubmit="return confirm('Terima pengembalian buku ini?')">
        @csrf
        @method('PUT')
        <button type="submit" class="btn-green" title="Terima" style="border:none; border-radius:4px; padding: 2px 8px; cursor:pointer;">✔</button>
    </form>
    <form action="{{ route('transactions.tolakPengembalian', $trx->id) }}" method="POST" onsubmit="return confirm('Tolak pengembalian buku ini?')">
        @csrf
        @method('PUT')
        <button type="submit" class="btn-red" title="Tolak" style="border:none; border-radius:4px; padding: 2px 8px; cursor:pointer;">✖</button>
    </form>
@elseif($trx->status == 'sudah_dikembalikan')
<span class="btn-filter btn-nota"
      onclick="window.open('{{ route('cetak.nota', [$trx->id, 'pengembalian']) }}', '_blank')">
    <i class="fa-solid fa-print"></i>
</span>@endif
</td>
</tr>
@empty
<tr>
    <td colspan="8" style="text-align:center">Tidak ada data</td>
</tr>
@endforelse
</tbody>
                <tfoot>
                    <tr>
                        <td colspan="8">
                            @include('components.pagination', ['paginator' => $transactions])
                        </td>
                    </tr>
                </tfoot>
</table>
</div>
@endif
@endsection

