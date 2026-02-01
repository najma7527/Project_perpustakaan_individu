@extends('layouts.app')

@section('title', 'Laporan Kehilangan Buku')
@push('styles')
   <link rel="stylesheet" href="{{ asset('css/laporan_data_kehilangan.css') }}">
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
                        <td>
                            <span class="{{ $i % 2 ? 'status-red' : 'status-green' }}">
                                {{ $i % 2 ? 'Belum dikembalikan' : 'Sudah dikembalikan' }}
                            </span>
                        </td>
                        <td class="aksi">
                            <button class="btn ok"><i class="fa fa-check"></i></button>
                            <button class="btn del"><i class="fa fa-xmark"></i></button>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>

    </main>
</div>

{{-- JS --}}
<script>
document.getElementById('toggleSidebar').onclick = function () {
    document.querySelector('.sidebar').classList.toggle('active');
};
</script>
@endsection