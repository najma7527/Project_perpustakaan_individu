@extends('layouts.app')

@section('title', 'Detail Buku')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/siswa/detail-buku.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')

<div class="container">
    <div class="book-detail">
        <div class="book-header">
            <div class="book-cover">
                <img src="{{ $book->cover ? asset('storage/' . $book->cover) : asset('images/buku.jpg') }}"
                     alt="{{ $book->judul }}" class="cover-image">
            </div>

            <div class="book-info">
                <h1 class="book-title">{{ $book->judul }}</h1>
                <p class="book-author">By: {{ $book->pengarang }}</p>

                <div class="book-meta">
                    <span class="meta-item">
                        <i class="fas fa-calendar"></i> {{ $book->tahun_terbit }}
                    </span>
                    <span class="meta-item">
                        <i class="fas fa-tag"></i> {{ ucfirst($book->kategori_buku) }}
                    </span>
                    <span class="meta-item">
                        <i class="fas fa-map-marker"></i>
                        {{ $book->row ? "Rak {$book->row->bookshelf->no_rak} - Baris {$book->row->baris_ke}" : 'Belum ditentukan' }}
                    </span>
                </div>

                <div class="book-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ $book->stok }}</span>
                        <span class="stat-label">Total Eksemplar</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $book->availableStock() }}</span>
                        <span class="stat-label">Tersedia</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $book->kodeBuku->where('status', 'dipinjam')->count() }}</span>
                        <span class="stat-label">Dipinjam</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $book->kodeBuku->where('status', 'hilang')->count() }}</span>
                        <span class="stat-label">Hilang</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="book-description">
            <h3>Sinopsis</h3>
            <p>{{ $book->deskripsi }}</p>
        </div>

        <div class="kode-buku-section">
            <h3>Daftar Kode Buku</h3>
            <div class="kode-buku-grid">
                @forelse($book->kodeBuku as $kode)
                <div class="kode-buku-card {{ $kode->status }}">
                    <div class="kode-header">
                        <span class="kode-number">{{ $kode->kode_buku }}</span>
                        <span class="kode-status {{ $kode->status }}">
                            @if($kode->status == 'tersedia')
                                <i class="fas fa-check-circle"></i> Tersedia
                            @elseif($kode->status == 'dipinjam')
                                <i class="fas fa-clock"></i> Dipinjam
                            @else
                                <i class="fas fa-exclamation-triangle"></i> Hilang
                            @endif
                        </span>
                    </div>
                </div>
                @empty
                <p class="no-kode">Belum ada kode buku untuk buku ini.</p>
                @endforelse
            </div>
        </div>

        <div class="action-buttons">
            <a href="{{ route('books.browse') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Buku
            </a>

            @if($book->availableStock() > 0)
            <a href="{{ route('books.browse') }}" class="btn-pinjam">
                <i class="fas fa-book"></i> Pinjam Buku Ini
            </a>
            @endif
        </div>
    </div>
</div>

<style>
.book-detail {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.book-header {
    display: flex;
    gap: 30px;
    margin-bottom: 30px;
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.book-cover img {
    width: 200px;
    height: 300px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.book-info {
    flex: 1;
}

.book-title {
    font-size: 2.5rem;
    margin-bottom: 10px;
    color: #333;
}

.book-author {
    font-size: 1.2rem;
    color: #666;
    margin-bottom: 20px;
}

.book-meta {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #555;
}

.book-stats {
    display: flex;
    gap: 30px;
    margin-top: 20px;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: #007bff;
    display: block;
}

.stat-label {
    font-size: 0.9rem;
    color: #666;
}

.book-description {
    background: white;
    padding: 30px;
    border-radius: 10px;
    margin-bottom: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.book-description h3 {
    margin-bottom: 15px;
    color: #333;
}

.kode-buku-section {
    background: white;
    padding: 30px;
    border-radius: 10px;
    margin-bottom: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.kode-buku-section h3 {
    margin-bottom: 20px;
    color: #333;
}

.kode-buku-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
}

.kode-buku-card {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    transition: all 0.3s ease;
}

.kode-buku-card.tersedia {
    border-color: #28a745;
    background: #f8fff9;
}

.kode-buku-card.dipinjam {
    border-color: #ffc107;
    background: #fffbf0;
}

.kode-buku-card.hilang {
    border-color: #dc3545;
    background: #fff5f5;
}

.kode-header {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.kode-number {
    font-size: 1.2rem;
    font-weight: bold;
    color: #333;
}

.kode-status {
    font-size: 0.9rem;
    padding: 4px 8px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.kode-status.tersedia {
    background: #d4edda;
    color: #155724;
}

.kode-status.dipinjam {
    background: #fff3cd;
    color: #856404;
}

.kode-status.hilang {
    background: #f8d7da;
    color: #721c24;
}

.action-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
}

.btn-back, .btn-pinjam {
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-back {
    background: #6c757d;
    color: white;
}

.btn-back:hover {
    background: #5a6268;
    color: white;
}

.btn-pinjam {
    background: #007bff;
    color: white;
}

.btn-pinjam:hover {
    background: #0056b3;
    color: white;
}

.no-kode {
    grid-column: 1 / -1;
    text-align: center;
    color: #666;
    font-style: italic;
    padding: 40px;
}
</style>

@endsection