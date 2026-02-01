@extends('layouts.app')

@section('title', 'Kelola Anggota - Ditolak')

@push('styles')
   <link rel="stylesheet" href="{{ asset('css/kelola-anggota-ditolak.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
@endpush

@section('content')


        <!-- TOPBAR -->
        <header class="topbar">
            <i class="fa fa-bars"></i>
            <div class="user">
                <div>
                    <strong>Seulgi</strong><br>
                    <small>Admin</small>
                </div>
                <img src="{{ asset('images/avatar.png') }}">
            </div>
        </header>

        <!-- CONTENT -->
        <section class="content">

            <!-- HEADER -->
            <div class="header-card">
                <div class="header-left">
                    <div class="header-icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div>
                        <h3>Kelola Anggota</h3>
                        <p>Mencatat data pengunjung perpustakaan</p>
                    </div>
                </div>
                <img src="{{ asset('img/book.png') }}" class="header-img">
            </div>

            <!-- TAB -->
            <div class="tab-wrapper">
                <a href="/kelola_anggota-verifikasi" class="tab-item">Verifikasi</a>
                <a href="/kelola_anggota-diterima" class="tab-item">Diterima</a>
                <a href="/kelola_anggota-ditolak" class="tab-item active">Ditolak</a>
            </div>

            <!-- TABLE -->
            <div class="table-card">

                <div class="filter">
                    <div class="search">
                        <i class="fa fa-search"></i>
                        <input type="text" placeholder="Cari sesuatu...">
                    </div>
                    <div class="date">
                        <i class="fa fa-calendar"></i>
                        <input type="date">
                    </div>
                </div>

                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>NIS</th>
                                <th>NISN</th>
                                <th>Kelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                        @for ($i = 1; $i <= 10; $i++)
                        <tr>
                            <td>{{ $i }}</td>

                            <!-- USER CELL -->
                            <td class="user-cell">
                                <img src="{{ asset('images/avatar.png') }}" class="avatar">
                                <div class="user-info">
                                    <strong>Erika Putri Himawan</strong>
                                    <small>@erikagemoi</small>
                                </div>
                            </td>

                            <td>6550/147.089</td>
                            <td>0087074225</td>
                            <td>X RPL {{ $i }}</td>

                            <td class="aksi">
                                <button class="btn-accept"><i class="fa fa-check"></i></button>
                                <button class="btn-reject"><i class="fa fa-times"></i></button>
                            </td>
                        </tr>
                        @endfor
                        </tbody>
                    </table>
                </div>

            </div>
        </section>
@endsection
