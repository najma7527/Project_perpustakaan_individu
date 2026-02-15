@extends('layouts.app')
@section('title', 'Edit Profile')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')
<div class="main-content">
    <div class="profile-header">
        <h2>Edit Profile</h2>
    </div>

    <div class="edit-card">
        @if(auth()->user()->role == 'admin')
        <div class="tab-nav">
            <button class="tab-btn active" id="btnProfil" onclick="showTab('profil')">Profil</button>
            <button class="tab-btn" id="btnPassword" onclick="showTab('password')">Reset Password</button>
        </div>
        @endif

        <!-- TAB PROFIL (untuk semua user) -->
        <div id="profilTab" class="tab-content">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="name" value="{{ auth()->user()->name }}" required>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="{{ auth()->user()->username }}" required>
                </div>
                <div class="form-group">
                    <label>No Telepon</label>
                    <input type="text" name="telephone" value="{{ auth()->user()->telephone }}">
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <input type="text" name="alamat" value="{{ auth()->user()->alamat }}">
                </div>

                @if(auth()->user()->role == 'anggota')
                <div class="form-group">
                    <label>NISN</label>
                    <input type="text" name="nis_nisn" value="{{ auth()->user()->nis_nisn }}">
                </div>
                <div class="form-group">
                    <label>Kelas</label>
                    <input type="text" name="kelas" value="{{ auth()->user()->kelas }}">
                </div>
                @endif

                <button type="submit" class="btn-save">Simpan Perubahan</button>
            </form>
        </div>

        <!-- TAB PASSWORD (khusus admin) -->
        <div id="passwordTab" class="tab-content" style="display:none;">
            <form action="{{ route('profile.updatePassword') }}" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required>
                </div>
                <button type="submit" class="btn-save">Reset Password</button>
            </form>
        </div>
    </div>
</div>

@if(auth()->user()->role == 'admin')
<script>
    function showTab(tab) {
        const profil = document.getElementById('profilTab');
        const password = document.getElementById('passwordTab');
        const btnProfil = document.getElementById('btnProfil');
        const btnPassword = document.getElementById('btnPassword');

        if (tab === 'profil') {
            profil.style.display = 'block';
            password.style.display = 'none';
            btnProfil.classList.add('active');
            btnPassword.classList.remove('active');
        } else {
            profil.style.display = 'none';
            password.style.display = 'block';
            btnPassword.classList.add('active');
            btnProfil.classList.remove('active');
        }
    }
</script>
@endif
@endsection