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
            <div class="search">
                <i class="fa fa-search"></i>
                <input type="text" placeholder="Cari sesuatu...">
            </div>

            <div class="date">
                <i class="fa fa-calendar"></i>
                <input type="date">
            </div>

            <button class="btn-print">Cetak Laporan</button>
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
                    @for($i=1;$i<=10;$i++)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>Erika Putri</td>
                        <td>Laut Bercerita</td>
                        <td>X TP 2</td>
                        <td>20/01/2026</td>
                        <td>20/01/2026</td>
                        @php
                            if ($i % 3 == 0) {
                                $status = 'Menunggu konfirmasi';
                                $statusClass = 'status-yellow';
                            } elseif ($i % 2) {
                                $status = 'Belum dikembalikan';
                                $statusClass = 'status-red';
                            } else {
                                $status = 'Sudah dikembalikan';
                                $statusClass = 'status-green';
                            }
                        @endphp
                        <td>
                            <span class="{{ $statusClass }}">
                                {{ $status }}
                            </span>
                        </td>
                        <td class="aksi">
                            <td class="aksi">
    @if($status === 'Menunggu konfirmasi')
        <button class="btn ok"><i class="fa fa-check"></i></button>
        <button class="btn del"><i class="fa fa-xmark"></i></button>
    @else
        <span class="no-action">-</span>
    @endif
</td>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>

    </main>
</div>

{{-- JS --}}
@push('scripts')
<script>
document.getElementById('toggleSidebar')?.addEventListener('click', function () {
    document.querySelector('.sidebar')?.classList.toggle('active');
});
</script>
@endpush
@endsection
