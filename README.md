# 📚 Perpustakaan Sekolah

> **Kelola perpustakaan sekolah dengan lebih mudah** — buku, anggota, transaksi, dan laporan dalam satu aplikasi.

<p align="center">
  <img alt="Laravel" src="https://img.shields.io/badge/Laravel-12-red?logo=laravel">
  <img alt="PHP" src="https://img.shields.io/badge/PHP-8.2-blue?logo=php">
  <img alt="Status" src="https://img.shields.io/badge/Status-Prototype-orange">
  <img alt="License" src="https://img.shields.io/badge/License-MIT-green">
</p>

---

## ✨ Fitur Unggulan

| Fitur | Deskripsi |
|---|---|
| 📘 **Manajemen Buku** | CRUD, impor, dan ekspor data buku |
| 🗂️ **Rak & Baris** | Atur lokasi fisik setiap buku |
| 🧾 **Peminjaman** | Pinjam, kembali, histori, dan denda |
| 👥 **Anggota** | Registrasi dan riwayat peminjaman |
| 📊 **Laporan** | Kunjungan, transaksi, dan buku hilang |
| 🔐 **Role** | Admin, Petugas, dan Anggota |

## 🖼️ Tampilan Aplikasi

> Simpan gambar di folder `docs/screenshots/`

| Landing | Dashboard Admin |
|---|---|
| ![](docs/screenshots/landing.png) | ![](docs/screenshots/admin-dashboard.png) |

| Dashboard Anggota | Transaksi |
|---|---|
| ![](docs/screenshots/user-dashboard.png) | ![](docs/screenshots/transaksi.png) |

## 🛠️ Teknologi

<p align="center">
  <img src="https://skillicons.dev/icons?i=laravel,php,js,html,css,vite,mysql,git" alt="Tech Stack">
</p>

## 🚀 Instalasi

```bash
git clone https://github.com/najma7527/Project_perpustakaan_individu.git
cd Project_perpustakaan_individu

composer install
cp .env.example .env
php artisan key:generate

# atur koneksi database di .env

php artisan migrate --seed
npm install
npm run dev
php artisan serve
```

Buka **http://127.0.0.1:8000**

## 📂 Struktur Singkat

```text
app/
resources/views/
routes/
public/
docs/screenshots/
```

## 👥 Peran Pengguna

| Peran | Akses |
|---|---|
| **Admin** | Kelola seluruh data dan laporan |
| **Petugas** | Kelola transaksi dan buku |
| **Anggota** | Lihat katalog dan riwayat peminjaman |

## 🤝 Kontribusi

1. Fork repositori
2. Buat branch fitur (`git checkout -b fitur-baru`)
3. Commit perubahan
4. Push dan buka Pull Request

## 📄 Lisensi

Dirilis dengan lisensi **MIT**.
