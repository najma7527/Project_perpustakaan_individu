@extends('layouts.app')

@section('title', 'Laporan Kehilangan Buku')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/laporan_data_kehilangan.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')

<div class="header-card">
    <div class="header-left">
        <div class="header-icon">
            <i class="fa fa-file"></i>
        </div>
        <div class="header-text">
            <h3>Laporan Kehilangan Buku</h3>
            <p>Peminjman dan pengembalian buku</p>
        </div>
    </div>

    <img src="{{ asset('img/book.png') }}" class="header-image">
</div>

{{-- FILTER --}}
<div class="filter">

    {{-- SEARCH --}}
    <form method="GET" style="display:flex; gap:10px; align-items:center;">
        <div class="search">
            <i class="fa fa-search"></i>
            <input 
                type="text" 
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari sesuatu...">
        </div>

        <div class="date">
            <i class="fa fa-calendar"></i>
            <input type="date" name="date" value="{{ request('date') }}">
        </div>

        <button type="submit" style="display:none;"></button>
    </form>

    @auth
    <a href="{{ route('cetak.filter-kehilangan') }}" class="btn-print">
        <i class="fa-solid fa-print"></i>
        Cetak Laporan
    </a>

</div>

{{-- TABLE --}}
<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Anggota</th>
                <th>Judul Buku</th>
                <th>Kelas</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Mengganti</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse($reports as $index => $report)

        @php
        switch($report->status){
            case 'pending':
                $status = 'Menunggu Konfirmasi';
                $statusClass = 'status-yellow';
                break;

            case 'belum_dikembalikan':
                $status = 'Belum Dikembalikan';
                $statusClass = 'status-red';
                break;

            case 'sudah_dikembalikan':
                $status = 'Sudah Dikembalikan';
                $statusClass = 'status-green';
                break;

            default:
                $status = ucfirst(str_replace('_', ' ', $report->status));
                $statusClass = 'status-gray';
        }
        @endphp

        <tr>
            <td>{{ $reports->firstItem() + $index }}</td>

            <td>{{ $report->transaction->user->name ?? '-' }}</td>

            <td>{{ $report->transaction->book->judul ?? '-' }}</td>

            <td>{{ $report->transaction->user->kelas ?? '-' }}</td>

            <td>
                {{ optional($report->transaction->tanggal_peminjaman)->format('d/m/Y') }}
            </td>

            <td>
                {{ $report->tanggal_ganti ? \Carbon\Carbon::parse($report->tanggal_ganti)->format('d/m/Y') : '-' }}
            </td>

            <td>
                <span class="{{ $statusClass }}">
                    {{ $status }}
                </span>
            </td>

            <td class="aksi">
                @if($report->status === 'pending')
                    <form action="{{ route('reports.approve', $report->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn ok" title="Setujui">
                            <i class="fa fa-check"></i>
                        </button>
                    </form>

                    <form action="{{ route('reports.reject', $report->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn del" title="Tolak">
                            <i class="fa fa-xmark"></i>
                        </button>
                    </form>
                @elseif($report->status === 'sudah_dikembalikan')
                    <span class="btn-filter btn-nota" data-nama="{{ $report->transaction->user->name }}"><i class="fa-solid fa-print"></i></span>
                @else
                    <span class="no-action">-</span>
                @endif
            </td>
        </tr>

        @empty
        <tr>
            <td colspan="8" style="text-align:center">Data tidak ada</td>
        </tr>
        @endforelse
        </tbody>

    </table>
{{-- PAGINATION --}}
<div style="margin-top:20px;">
    <div class="table-pagination">
        <span class="page-info">Menampilkan {{ $reports->firstItem() }}–{{ $reports->lastItem() }} dari {{ $reports->total() }} data</span>

        <div class="pagination">
            @if ($reports->onFirstPage())
                <span class="page-btn disabled"><i class="fa fa-chevron-left"></i></span>
            @else
                <a href="{{ $reports->previousPageUrl() }}" class="page-btn"><i class="fa fa-chevron-left"></i></a>
            @endif

            @php $current = $reports->currentPage(); $last = $reports->lastPage(); @endphp

            @if ($current == 1)
                <span class="page-btn active">1</span>
            @else
                <a href="{{ $reports->url(1) }}" class="page-btn">1</a>
            @endif

            @if ($current > 1)
                <span class="page-btn active">{{ $current }}</span>
            @endif

            @if ($current + 1 <= $last)
                <a href="{{ $reports->url($current + 1) }}" class="page-btn">{{ $current + 1 }}</a>
            @endif

            @if ($current + 1 < $last)
                <span class="page-dots">…</span>
            @endif

            @if ($last > 1)
                <a href="{{ $reports->url($last) }}" class="page-btn">{{ $last }}</a>
            @endif

            @if ($reports->hasMorePages())
                <a href="{{ $reports->nextPageUrl() }}" class="page-btn"><i class="fa fa-chevron-right"></i></a>
            @else
                <span class="page-btn disabled"><i class="fa fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
</div>
</div>

@endsection

{{-- JS --}}
@push('scripts')
<script>
document.getElementById('toggleSidebar')?.addEventListener('click', function () {
    document.querySelector('.sidebar')?.classList.toggle('active');
});
</script>
@endpush