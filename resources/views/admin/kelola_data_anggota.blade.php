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
                    <div class="search">
                        <i class="fa fa-search"></i>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari sesuatu...">
                    </div>
                    <div class="date">
                        <i class="fa fa-calendar"></i>
                        <input type="date" name="date" value="{{ $date }}">
                    </div>
                    <button type="submit" class="btn-filter">
                        <i class="fa fa-sliders"></i>
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
                                                name: '{{ addslashes($user->name) }}',
                                                username: '{{ $user->username }}',
                                                telephone: '{{ $user->telephone ?? '-' }}',
                                                nis_nisn: '{{ $user->nis_nisn ?? '-' }}',
                                                kelas: '{{ $user->kelas ?? '-' }}',
                                                status: '{{ $user->status }}',
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
                                        <button type="button" class="delete" title="Hapus" onclick="confirmDelete('{{ $user->id }}', '{{ addslashes($user->name) }}')">
                                            <i class="fa fa-trash"></i>
                                        </button>
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
                        <div class="form-group">
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
 @endif

<!-- MODAL DETAIL - Hanya muncul di tab Diterima -->
@if($tab == 'diterima')
<div class="modal" id="detailModal">
    <div class="modal-box">
        <button class="btn-modal-close" onclick="closeDetailModal()">&times;</button>
        
        <div class="modal-header">
            <h3><i class="fa fa-info-circle"></i> Detail Anggota</h3>
        </div>

        <div class="modal-content-wrapper">
            <div class="detail-content">
                <div class="detail-section">
                    <h4 class="section-title">Informasi Pribadi</h4>
                    <div class="detail-row">
                        <label>Nama Lengkap</label>
                        <p id="detail_name">-</p>
                    </div>
                    <div class="detail-row">
                        <label>Username</label>
                        <p id="detail_username">-</p>
                    </div>
                    <div class="detail-row">
                        <label>Nomor Telepon</label>
                        <p id="detail_telephone">-</p>
                    </div>
                </div>

                <div class="detail-section">
                    <h4 class="section-title">Informasi Akademik</h4>
                    <div class="detail-row">
                        <label>NIS / NISN</label>
                        <p id="detail_nis">-</p>
                    </div>
                    <div class="detail-row">
                        <label>Kelas</label>
                        <p id="detail_kelas">-</p>
                    </div>
                </div>

                <div class="detail-section">
                    <h4 class="section-title">Status & Tanggal</h4>
                    <div class="detail-row">
                        <label>Status</label>
                        <p id="detail_status">-</p>
                    </div>
                    <div class="detail-row">
                        <label>Tanggal Pendaftaran</label>
                        <p id="detail_date">-</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeDetailModal()">
                <i class="fa fa-times"></i> Tutup
            </button>
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