@extends('layouts.app')

@section('title', 'Kelola Anggota - ' . ucfirst($tab))

@push('styles')
    {{-- Load CSS sesuai tab --}}
    @if($tab == 'verifikasi')
        <link rel="stylesheet" href="{{ asset('css/admin/kelola-anggota-verifikasi.css') }}">
    @elseif($tab == 'diterima')
        <link rel="stylesheet" href="{{ asset('css/admin/kelola-anggota-diterima.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('css/admin/kelola-anggota-ditolak.css') }}">
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
                    <div class="filter-left">
                        <div class="search">
                            <i class="fa fa-search"></i>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Cari sesuatu...">
                        </div>
                        @if($tab == 'verifikasi')
                        <div class="date">
                            <i class="fa fa-calendar"></i>
                            <input type="date" name="date" value="{{ $date }}">
                        </div>
                        @endif
                    </div>
                    @if($tab == 'diterima')
                    <a href="{{ route('admin.anggota.exportExcel') }}" class="btn-export-excel">
                        <i class="fa fa-file-excel"></i> Export Excel
                    </a>
                    @endif
                </div>
            </form>

            <!-- TABEL -->
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>NIS</th>
                            <th>Kelas</th>
                            <th>Alamat</th>
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
                                <td>{{ $loop->iteration }}</td>
                                <td class="user-cell">
                                    @if($user->profile_photo && file_exists(public_path($user->profile_photo)))
                                        <img src="{{ asset($user->profile_photo) }}" class="avatar" alt="{{ $user->name }}">
                                    @else
                                        <div class="avatar avatar-default">
                                            <i class="fa fa-user"></i>
                                        </div>
                                    @endif
                                    <div class="user-info">
                                        <strong>{{ $user->name }}</strong>
                                        <small>@.{{ $user->username }}</small>
                                    </div>
                                </td>

                                <td>{{ $user->nis_nisn ?? '-' }}</td>
                                <td>{{ $user->kelas ?? '-' }}</td>
                                <td>{{ $user->alamat ?? '-' }}</td>

                                @if($tab == 'verifikasi')
                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                @elseif($tab == 'diterima')
                                    <td ><span class="status aktif">{{ $user->status }}</span></td>
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
                                        <button type="button" class="view" title="Lihat Detail"
                                            onclick="openDetailModal({
                                                id: '{{ $user->id }}',
                                                name: '{{ addslashes($user->name) }}',
                                                username: '{{ $user->username }}',
                                                telephone: '{{ $user->telephone ?? '-' }}',
                                                nis_nisn: '{{ $user->nis_nisn ?? '-' }}',
                                                kelas: '{{ $user->kelas ?? '-' }}',
                                                status: '{{ $user->status }}',
                                                alamat: '{{ addslashes($user->alamat ?? '-') }}',
                                                profile_photo: '{{ $user->profile_photo ? asset($user->profile_photo) : '' }}',
                                                created_at: '{{ $user->created_at }}'
                                            })">
                                            <i class="fa fa-eye"></i>
                                        </button>

                                        <button type="button" class="edit" title="Kelola"
                                            onclick="openEditModal('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ $user->username }}', '{{ $user->nis_nisn }}', '{{ $user->kelas }}')">
                                            <i class="fa fa-pen"></i>
                                        </button>

                                        <button type="button" class="delete" title="Hapus" onclick="confirmDelete('{{ $user->id }}', '{{ addslashes($user->name) }}')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @elseif($tab == 'ditolak')
                                        <form action="{{ route('admin.anggota.status', $user->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="status" value="aktif">
                                            <button type="submit" class="btn-accept"><i class="fa fa-check"></i></button>
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
                    <tfoot>
                        <tr>
                            <td colspan="7">
                                <div class="table-pagination">
                                    <span class="page-info">
                                        Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} data
                                    </span>

                                    <div class="pagination">
                                        @php
    $current = $users->currentPage();
    $last = $users->lastPage();
@endphp

{{-- PREV --}}
@if ($users->onFirstPage())
    <span class="page-btn disabled">
        <i class="fa fa-chevron-left"></i>
    </span>
@else
    <a href="{{ $users->appends(request()->query())->previousPageUrl() }}" class="page-btn">
        <i class="fa fa-chevron-left"></i>
    </a>
@endif

{{-- PAGE 1 --}}
@if ($current == 1)
    <span class="page-btn active">1</span>
@else
    <a href="{{ $users->appends(request()->query())->url(1) }}" class="page-btn">1</a>
@endif

{{-- CURRENT PAGE (jika bukan page 1) --}}
@if ($current > 1)
    <span class="page-btn active">{{ $current }}</span>
@endif

{{-- NEXT PAGE NUMBER --}}
@if ($current + 1 <= $last)
    <a href="{{ $users->appends(request()->query())->url($current + 1) }}" class="page-btn">
        {{ $current + 1 }}
    </a>
@endif

{{-- DOTS --}}
@if ($current + 1 < $last)
    <span class="page-dots">…</span>
@endif

{{-- LAST PAGE --}}
@if ($last > 1)
    <a href="{{ $users->appends(request()->query())->url($last) }}" class="page-btn">
        {{ $last }}
    </a>
@endif

{{-- NEXT --}}
@if ($users->hasMorePages())
    <a href="{{ $users->appends(request()->query())->nextPageUrl() }}" class="page-btn">
        <i class="fa fa-chevron-right"></i>
    </a>
@else
    <span class="page-btn disabled">
        <i class="fa fa-chevron-right"></i>
    </span>
@endif

                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            
                </table>
@if ($users->hasPages())
@endif


            </div>
        </div>

        <!-- MODAL EDIT - Hanya muncul di tab Diterima -->
        @if($tab == 'diterima')
<div class="modal" id="editModal">
    <div class="modal-box">
        <button class="btn-modal-close" onclick="closeModal()">&times;</button>
        
        <div class="modal-header">
            <h3>Kelola Anggota</h3>
        </div>

        <!-- TAB HEADER -->
        <div class="modal-tabs">
            <button class="modal-tab-btn active" data-tab="tab-data" onclick="switchTab(event, 'tab-data')">
                <i class="fa fa-user"></i> Data Siswa
            </button>
            <button class="modal-tab-btn" data-tab="tab-password" onclick="switchTab(event, 'tab-password')">
                <i class="fa fa-key"></i> Reset Password
            </button>
        </div>

        <!-- TAB CONTENT -->
        <div class="modal-content-wrapper">
            
            <!-- TAB 1: DATA SISWA & STATUS -->
            <div id="tab-data" class="modal-tab-content active">
                <form id="formEditStatus" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Nama Siswa</label>
                        <input type="text" id="edit_name" class="form-control" disabled>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input type="text" id="edit_username" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">NIS / NISN</label>
                            <input type="text" id="edit_nis" class="form-control" disabled>
                        </div>
                    </div>

                    <div class="form-row">
                        <div clasts="form-group">
                            <label class="form-label">Kelas</label>
                            <input type="text" id="edit_kelas" class="form-control" disabled>
                        </div>
                    </div>

                    <hr style="margin: 1.5rem 0; border: none; border-top: 1px solid #e0e0e0;">

                    <div class="form-group">
                        <label class="form-label">Status Anggota <span class="required">*</span></label>
                        <select name="status" id="edit_status" class="form-control" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Simpan Status
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 2: RESET PASSWORD -->
            <div id="tab-password" class="modal-tab-content">
                <form id="formResetPassword" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Password Baru <span class="required">*</span></label>
                        <input type="password" name="password" id="new_password" class="form-control" 
                               placeholder="Minimal 6 karakter" required minlength="6">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password <span class="required">*</span></label>
                        <input type="password" name="password_confirmation" id="confirm_password" class="form-control" 
                               placeholder="Konfirmasi password" required minlength="6">
                    </div>

                    <div class="password-info" style="background-color: #e8f4f8; padding: 10px; border-radius: 4px; margin: 1rem 0; font-size: 0.9rem; color: #0066cc;">
                        <i class="fa fa-info-circle"></i> Password minimal harus 6 karakter
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-key"></i> Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
 @endif

<!-- MODAL DETAIL SISWA - Redesigned -->
@if($tab == 'diterima')
<div class="modal" id="detailModal">
    <div class="detail-modal-box">

        <!-- Header Biru -->
        <div class="detail-modal-header">
            <h3>Detail Siswa</h3>
        </div>

        <!-- Foto Profil Bulat -->
        <div class="detail-photo-wrapper">
            <img id="detail_photo" src="{{ asset('img/profile.png') }}" alt="Foto Profil" class="detail-photo">
        </div>

        <!-- Data 2 Kolom -->
        <div class="detail-grid">
            <div class="detail-field">
                <span class="detail-label">Nama:</span>
                <span class="detail-value" id="detail_name">-</span>
            </div>
            <div class="detail-field">
                <span class="detail-label">Status:</span>
                <span class="detail-value" id="detail_status">-</span>
            </div>

            <div class="detail-field">
                <span class="detail-label">Username:</span>
                <span class="detail-value" id="detail_username">-</span>
            </div>
            <div class="detail-field">
                <span class="detail-label">No. Telp:</span>
                <span class="detail-value" id="detail_telephone">-</span>
            </div>

            <div class="detail-field">
                <span class="detail-label">NIS:</span>
                <span class="detail-value" id="detail_nis">-</span>
            </div>
            <div class="detail-field">
                <span class="detail-label">Alamat:</span>
                <span class="detail-value" id="detail_alamat">-</span>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="detail-modal-footer">
            <button type="button" id="detail_cetak_btn" class="btn-cetak-kartu">
                <i class="fa fa-id-card"></i> Cetak Kartu Anggota
            </button>
            <button class="btn-tutup" onclick="closeDetailModal()">Tutup</button>
        </div>

    </div>
</div>
@endif
<form id="deleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
<script src="{{ asset('js/kelola_anggota.js') }}"></script>

@endsection