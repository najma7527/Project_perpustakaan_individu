@extends('layouts.app')

@section('title', 'Kelola Anggota - ' . ucfirst($tab))

@push('styles')
    {{-- Load CSS sesuai tab --}}
    @if($tab == 'verifikasi')
        <link rel="stylesheet" href="{{ asset('css/kelola-anggota-verifikasi.css') }}">
    @elseif($tab == 'diterima')
        <link rel="stylesheet" href="{{ asset('css/kelola-anggota-diterima.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('css/kelola-anggota-ditolak.css') }}">
    @endif
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
@endpush

@section('content')
        <!-- HEADER CARD -->
        <div class="header-card">
            <div class="header-left">
                <div class="header-icon">
                    <i class="fa fa-user-check"></i>
                </div>
                <div>
                    <h3>Kelola Anggota</h3>
                    <p>
                        @if($tab == 'verifikasi') Daftar anggota menunggu verifikasi
                        @elseif($tab == 'diterima') Daftar anggota yang telah diterima
                        @else Daftar anggota ditolak / non-aktif
                        @endif
                    </p>
                </div>
            </div>
            <img src="{{ asset('img/book.png') }}" alt="book">
        </div>

        <!-- TAB -->
        <div class="tab-wrapper">
            <a href="{{ route('admin.anggota.index', ['tab' => 'verifikasi']) }}" class="tab-item {{ $tab == 'verifikasi' ? 'active' : '' }}">Verifikasi</a>
            <a href="{{ route('admin.anggota.index', ['tab' => 'diterima']) }}"   class="tab-item {{ $tab == 'diterima'   ? 'active' : '' }}">Diterima</a>
            <a href="{{ route('admin.anggota.index', ['tab' => 'ditolak']) }}"    class="tab-item {{ $tab == 'ditolak'    ? 'active' : '' }}">Ditolak</a>
        </div>

        <!-- FILTER -->
        <div class="table-card">
            <form method="GET" action="{{ route('admin.anggota.index') }}">
                <input type="hidden" name="tab" value="{{ $tab }}">

                <div class="filter">
                    <div class="search">
                        <i class="fa fa-search"></i>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari sesuatu...">
                    </div>
                    <div class="date">
                        <i class="fa fa-calendar"></i>
                        <input type="date" name="date" value="{{ $date }}">
                    </div>
                    <button type="submit" class="btn-filter">
                        <i class="fa fa-filter"></i>
                    </button>
                </div>
            </form>

            <!-- TABEL -->
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>NIS / NISN</th>
                            <th>Kelas</th>
                            @if($tab == 'verifikasi')
                                <th>Tanggal Daftar</th>
                            @elseif($tab == 'diterima')
                                <th>Status</th>
                            @endif
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                            <tr>
                                <td>{{ $users->firstItem() + $index }}</td>

                                <td class="user-cell">
                                    <img src="{{ asset('images/avatar.png') }}" class="avatar" alt="avatar">
                                    <div class="user-info">
                                        <strong>{{ $user->name }}</strong>
                                        <small>@{{ $user->username }}</small>
                                    </div>
                                </td>

                                <td>{{ $user->nis_nisn ?? '-' }}</td>
                                <td>{{ $user->kelas ?? '-' }}</td>

                                @if($tab == 'verifikasi')
                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                @elseif($tab == 'diterima')
                                    <td><span class="status aktif">Diterima</span></td>
                                @endif

                                <td class="aksi">
                                    @if($tab == 'verifikasi')
                                        <form action="{{ route('admin.anggota.status', $user->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="status" value="aktif">
                                            <button type="submit" class="yes"><i class="fa fa-check"></i></button>
                                        </form>
                                        <form action="{{ route('admin.anggota.status', $user->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="status" value="ditolak">
                                            <button type="submit" class="no"><i class="fa fa-times"></i></button>
                                        </form>

                                    @elseif($tab == 'diterima')
                                        <button class="edit" title="Edit"><i class="fa fa-pen"></i></button>
                                        <a href="#" class="view"><i class="fa fa-eye"></i></a>
                                        <form action="{{ route('admin.anggota.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="delete"><i class="fa fa-trash"></i></button>
                                        </form>

                                    @elseif($tab == 'ditolak')
                                        <form action="{{ route('admin.anggota.status', $user->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="status" value="aktif">
                                            <button type="submit" class="btn-accept"><i class="fa fa-check"></i> Terima kembali</button>
                                        </form>
                                        <form action="{{ route('admin.anggota.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="delete"><i class="fa fa-trash"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center; padding:2rem;">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
@endsection