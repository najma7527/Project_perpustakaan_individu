@extends('layouts.app')
@section('title', 'Profile Saya')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')
<div class="main-content"> <!-- jika layout belum menyediakan, tambahkan sendiri -->
    <div class="profile-header" style="background: linear-gradient(135deg, #0d47a1, #1e88e5); color: white; padding: 1.5rem 2rem; border-radius: 12px; margin-bottom: 2rem;">
        <h2 style="margin:0;">Profile Saya</h2>
    </div>

    <div class="profile-grid" style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem; align-items: start;">
        <!-- Kartu kiri: foto & nama -->
        <div class="card" style="text-align: center; padding: 2rem;">
            <div class="avatar1" onclick="openModal()" style="position: relative; width: 120px; height: 120px; margin: 0 auto 1rem; cursor: pointer;">
                <img src="{{ auth()->user()->profile_photo ? asset('storage/'.auth()->user()->profile_photo) : asset('img/avatar.png') }}" 
                     alt="Avatar1" style="width:100%; height:100%; object-fit:cover; border-radius:50%; border:4px solid #1e88e5;">
                <span class="edit-icon" style="position:absolute; bottom:5px; right:5px; background:#1e88e5; color:white; width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; border:2px solid white;">
                    <i class="fa fa-pen"></i>
                </span>
            </div>
            <h3>{{ auth()->user()->name }}</h3>
            <span class="role" style="display:inline-block; background:#e3f2fd; color:#1e88e5; font-weight:600; padding:0.3rem 1rem; border-radius:20px;">{{ ucfirst(auth()->user()->role) }}</span>
        </div>

        <!-- Kartu kanan: data diri -->
        <div class="card" style="position:relative; padding:1.5rem;">
            <h4 style="font-size:1.25rem; margin-bottom:1.5rem; border-bottom:2px solid #1e88e5; padding-bottom:0.5rem; display:inline-block;">Data Diri</h4>
            <div style="margin-bottom:0.75rem; display:flex;">
                <strong style="min-width:120px; color:#6c757d;">Nama</strong>
                <span>: {{ auth()->user()->name }}</span>
            </div>
            <div style="margin-bottom:0.75rem; display:flex;">
                <strong style="min-width:120px; color:#6c757d;">Username</strong>
                <span>: {{ auth()->user()->username }}</span>
            </div>
            <div style="margin-bottom:0.75rem; display:flex;">
                <strong style="min-width:120px; color:#6c757d;">No. Telepon</strong>
                <span>: {{ auth()->user()->telephone ?? '-' }}</span>
            </div>
            <div style="margin-bottom:0.75rem; display:flex;">
                <strong style="min-width:120px; color:#6c757d;">Alamat</strong>
                <span>: {{ auth()->user()->alamat ?? '-' }}</span>
            </div>
            @if(auth()->user()->role == 'anggota')
                <div style="margin-bottom:0.75rem; display:flex;">
                    <strong style="min-width:120px; color:#6c757d;">Kelas</strong>
                    <span>: {{ auth()->user()->kelas ?? '-' }}</span>
                </div>
                <div style="margin-bottom:0.75rem; display:flex;">
                    <strong style="min-width:120px; color:#6c757d;">NISN</strong>
                    <span>: {{ auth()->user()->nis_nisn ?? '-' }}</span>
                </div>
            @endif
            <a href="{{ route('profile.edit') }}" class="btn-edit" style="position:absolute; top:1.5rem; right:1.5rem; background:#1e88e5; color:white; padding:0.5rem 1.2rem; border-radius:30px; text-decoration:none; display:inline-flex; align-items:center; gap:0.5rem;">
                <i class="fa fa-pen"></i> Edit Profil
            </a>
        </div>
    </div>

    @if(auth()->user()->role == 'anggota')
    <div class="card" style="margin-top:1.5rem; padding:1.5rem; overflow-x:auto;">
        <h4 style="margin-bottom:1.2rem;">Riwayat Kunjungan Saya</h4>
        <table style="width:100%; border-collapse:collapse; min-width:500px;">
            <thead>
                <tr style="background:#1e88e5; color:white;">
                    <th style="padding:0.75rem 1rem; text-align:left;">Aktivitas</th>
                    <th style="padding:0.75rem 1rem; text-align:left;">Nama Buku</th>
                    <th style="padding:0.75rem 1rem; text-align:left;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $item)
                <tr style="border-bottom:1px solid #e9ecef;">
                    <td style="padding:0.75rem 1rem;">{{ $item->transaction->jenis_transaksi ?? '-' }}</td>
                    <td style="padding:0.75rem 1rem;">{{ $item->transaction->book->judul ?? '-' }}</td>
                    <td style="padding:0.75rem 1rem;">{{ \Carbon\Carbon::parse($item->tanggal_datang)->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="padding:1rem; text-align:center;">Belum ada riwayat kunjungan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif
</div>

<!-- MODAL EDIT FOTO -->
@include('auth.profile.edit-foto-modal')

<script>
    function openModal() {
        document.getElementById('modalOverlay').style.display = 'flex';
    }
    function closeModal() {
        document.getElementById('modalOverlay').style.display = 'none';
    }
</script>
@endsection