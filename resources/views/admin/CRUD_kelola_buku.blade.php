@extends('layouts.app')

@section('title', $book ? 'Edit Data Buku' : 'Tambah Data Buku')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/CRUD_kelola_buku.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush    

@section('content')

<div class="header-card">
    <div>
        <h2>{{ $book ? 'Edit Data Buku' : 'Tambah Data Buku' }}</h2>
        <p>Mengelola data buku perpustakaan</p>
    </div>
    📚
</div>

<div class="card">

<form 
    action="{{ $book ? route('books.update',$book->id) : route('books.store') }}"
    method="POST"
    enctype="multipart/form-data"
>
@csrf
@if($book)
@method('PUT')
@endif

<div class="form-grid">

<!-- Judul Buku -->
<div class="form-group col-1">
    <label>Judul Buku</label>
    <input type="text" name="judul"
    value="{{ old('judul', $book->judul ?? '') }}"
    placeholder="Masukkan Judul Buku">

    @error('judul')
    <small class="error">Judul wajib diisi</small>
    @enderror
</div>

<!-- Kategori Buku -->
<div class="form-group col-2">
    <label>Kategori Buku</label>
    <input type="text" name="kategori"
    value="{{ old('kategori', $book->kategori ?? '') }}"
    placeholder="Pilih Kategori Buku">

    @error('kategori')
    <small class="error">Kategori wajib diisi</small>
    @enderror
</div>

<!-- Pengarang Buku -->
<div class="form-group col-1">
    <label>Pengarang Buku</label>
    <input type="text" name="pengarang"
    value="{{ old('pengarang', $book->pengarang ?? '') }}"
    placeholder="Masukkan Pengarang Buku">

    @error('pengarang')
    <small class="error">Pengarang wajib diisi</small>
    @enderror
</div>

<!-- Nomor Rak -->
<div class="form-group col-1">
    <label>Nomor Rak</label>
    <input type="text" name="nomor_rak"
    value="{{ old('nomor_rak', $book->nomor_rak ?? '') }}"
    placeholder="Masukkan Nomor Rak">

    @error('nomor_rak')
    <small class="error">Nomor rak wajib diisi</small>
    @enderror
</div>

<!-- Baris ke -->
<div class="form-group col-1">
    <label>Baris ke</label>
    <input type="text" name="baris"
    value="{{ old('baris', $book->baris ?? '') }}"
    placeholder="Masukkan Baris Rak ke-">

    @error('baris')
    <small class="error">Baris rak wajib diisi</small>
    @enderror
</div>

<!-- Tahun Terbit -->
<div class="form-group col-1">
    <label>Tahun Terbit</label>
    <input type="number" name="tahun_terbit"
    value="{{ old('tahun_terbit', $book->tahun_terbit ?? '') }}"
    placeholder="Masukkan Tahun Terbit">

    @error('tahun_terbit')
    <small class="error">Tahun terbit wajib diisi</small>
    @enderror
</div>

<!-- Kode Buku -->
<div class="form-group col-1">
    <label>Kode Buku</label>
    <input type="text" name="kode_buku"
    value="{{ old('kode_buku', $book->kode_buku ?? '') }}"
    placeholder="Masukkan Kode Buku">

    @error('kode_buku')
    <small class="error">Kode buku wajib diisi</small>
    @enderror
</div>

</div>

<!-- COVER -->
<div class="form-group" style="margin-top:20px">
<label>Cover Buku</label>

@if($book && $book->cover)
<div style="margin-bottom:10px">
<img src="{{ asset('storage/'.$book->cover) }}" width="120">
</div>
@endif

<div class="upload-box">
    <input type="file" name="cover" style="border:none">
</div>

@error('cover')
<span class="error">Cover wajib diisi</span>
@enderror
</div>

<!-- SINOPSIS -->
<div class="form-group" style="margin-top:20px">
<label>Sinopsis Buku</label>

<textarea name="sinopsis" class="editor">
{{ old('sinopsis', $book->sinopsis ?? '') }}
</textarea>

@error('sinopsis')
<span class="error">Sinopsis wajib diisi</span>
@enderror
</div>

<button class="btn">
{{ $book ? 'Update Buku' : 'Simpan Buku' }}
</button>

</form>

</div>

@endsection