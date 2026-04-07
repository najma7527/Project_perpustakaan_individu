@extends('layouts.app')

@section('title', 'Detail Buku')

@push('styles')
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
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.book-cover {
    flex-shrink: 0;
}

.cover-image {
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
    font-size: 2rem;
    font-weight: bold;
    color: #333;
    margin-bottom: 10px;
}

.book-author {
    font-size: 1.1rem;
    color: #666;
    margin-bottom: 20px;
}

.book-meta {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #555;
}

.book-stats {
    display: flex;
    gap: 20px;
    margin-top: 20px;
}

.stat-item {
    text-align: center;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    min-width: 80px;
}

.stat-number {
    display: block;
    font-size: 1.5rem;
    font-weight: bold;
    color: #007bff;
}

.stat-label {
    font-size: 0.9rem;
    color: #666;
}

.book-description {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.book-description h3 {
    color: #333;
    margin-bottom: 15px;
    font-size: 1.3rem;
}

.kode-buku-section {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.kode-buku-section h3 {
    color: #333;
    margin-bottom: 20px;
    font-size: 1.3rem;
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
    background: #fffef8;
}

.kode-buku-card.hilang {
    border-color: #dc3545;
    background: #fff8f8;
}

.kode-header {
    display: flex;
    flex-direction: column;
    gap: 10px;
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
    font-weight: 500;
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

.btn-back, .btn-edit {
    padding: 12px 24px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-back {
    background: #6c757d;
    color: white;
}

.btn-back:hover {
    background: #5a6268;
    color: white;
}

.btn-edit {
    background: #007bff;
    color: white;
}

.btn-edit:hover {
    background: #0056b3;
    color: white;
}

.no-kode {
    text-align: center;
    color: #666;
    font-style: italic;
    padding: 20px;
}
</style>
@endpush

@section('content')
<div class="book-detail">
    <div class="book-header">
        <div class="book-cover">
            <img src="{{ $book->cover ? asset('storage/' . $book->cover) : asset('img/buku.png') }}"
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
        <p>{{ $book->deskripsi ?: 'Tidak ada deskripsi tersedia.' }}</p>
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
        <a href="{{ route('books.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Buku
        </a>
        <a href="{{ route('books.edit', $book->id) }}" class="btn-edit">
            <i class="fas fa-edit"></i> Edit Buku
        </a>
    </div>
</div>
@endsection