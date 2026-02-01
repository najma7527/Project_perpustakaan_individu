@extends('layouts.app')

@section('title', 'Daftar Pengunjung')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/daftar_pengunjung.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')
    <!-- HEADER CARD -->
    <div class="header-card">
        <div class="header-left">
            <div class="header-icon">
                <i class="fa fa-user"></i>
            </div>
            <div>
                <h3>Daftar Pengunjung</h3>
                <p>Mencatat data pengunjung perpustakaan</p>
            </div>
        </div>
        <img src="{{ asset('img/book.png') }}" class="header-img">
    </div>

    <!-- TABLE CARD -->
    <div class="table-card">

        <div class="table-header">
    <form method="GET" action="{{ route('visits.index') }}">
        <div class="filter">
            <div class="search">
                <i class="fa fa-search"></i>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Cari nama pengunjung..."
                >
            </div>

            <div class="date">
                <i class="fa fa-calendar"></i>
                <input 
                    type="date" 
                    name="date"
                    value="{{ request('date') }}"
                >
            </div>

            <button type="submit" class="btn-filter">
                <i class="fa fa-filter"></i>
            </button>
            <button class="btn-print">Cetak Laporan</button>
        </div>
    </form>
</div>


        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pengunjung</th>
                        <th>Transaksi</th>
                        <th>Kelas</th>
                        <th>Tanggal Datang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
@forelse ($visits as $visit)
<tr>
    <td>{{ $loop->iteration }}</td>

    <td>{{ $visit->user->name }}</td>

    <td>
        {{ $visit->transaction?->status ?? 'Tidak ada transaksi' }}
    </td>

    <td>
        {{ $visit->user->kelas ?? '-' }}
    </td>

    <td>
        {{ \Carbon\Carbon::parse($visit->tanggal_datang)->format('d/m/Y') }}
    </td>

    <td>
        <button 
            class="btn-delete" 
            data-id="{{ $visit->id }}"
        >
            <i class="fa fa-trash"></i>
        </button>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" style="text-align:center;">
        Tidak ada data kunjungan
    </td>
</tr>
@endforelse
</tbody>

            </table>
        </div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function () {
            if (!confirm('Yakin ingin menghapus data kunjungan ini?')) return;

            fetch(`{{ route('visits.destroy', ':id') }}`.replace(':id', this.dataset.id), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Gagal menghapus data');
                return res.json();
            })
            .then(() => location.reload())
            .catch(err => alert(err.message));
        });
    });
});
</script>
@endpush

    </div>
@endsection