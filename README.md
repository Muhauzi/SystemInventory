# Inventory Management System

Inventory Management System adalah aplikasi berbasis web yang dibangun menggunakan [Laravel](https://laravel.com/). Aplikasi ini bertujuan untuk mengelola barang inventaris perusahaan, peminjaman inventaris, laporan barang invetaris, peminjaman barang inventaris, laporan kerusakan barang dan penagihan kerusakan dengan API Midtrans.

## Fitur

- **Manajemen Pengguna**: Registrasi, login, dan pengelolaan profil.
- **Manajemen Inventaris**: Pengelolaan Barang Inventaris.
- **Manajemen Peminjaman**: Pengelolaan peminjaman, persetujuan pinjaman, pengembalian barang, cetak bukti laporan peminjaman.
- **QR Scan**: Untuk menambahkan barang saat peminjaman barang, menampilkan detail barang, menampilkan data peminjaman.
- **Laporan Peminjaman**: Log Peminjaman dan pengembalian, export laporan menjadi excel.
- **Laporan Kerusakan Barang**: Data barang dengan status rusak saat dikembalikan, membuat penagihan kerusakan kepada peminjam.
- **Integrasi Pembayaran Tagihan**: Pembayaran tagihan kerusakan dengan Midtrans.
- **Return Reminder**: Email pengingat otomatis dikirimkan kepada peminjam h-3 tenggat pengembalian.

## Teknologi yang Digunakan

- **Framework**: Laravel 11
- **Database**: MySQL
- **Frontend**: Blade (Laravel Template Engine), Bootstrap
- **Server**: Apache/Nginx
- **Tools Lainnya**: Composer, Artisan CLI, PHPWord, PHPExcel, dan Laravel Mix.

## Persyaratan Sistem

- PHP >= 8.3
- Composer
- MySQL >= 8.0
- Node.js >= 16 (untuk pengelolaan aset)

## Instalasi

1. Clone repositori ini ke lokal Anda:
   ```bash
   git clone https://github.com/Muhauzi/SystemInventory.git
   ```
2. Masuk ke direktori proyek:
   ```bash
   cd SystemInventory
   ```
3. Instal dependensi menggunakan Composer:
   ```bash
   composer install
   ```
4. Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
5. Atur konfigurasi database, midtrans gateway, dan smtp di file `.env`.

6. Generate application key:
   ```bash
   php artisan key:generate
   ```
7. Jalankan migrasi database:
   ```bash
   php artisan migrate
   ```
8. (Opsional) Seed data ke database:
   ```bash
   php artisan db:seed
   ```
9. Jalankan server pengembangan lokal:
   ```bash
   php artisan serve
   ```
   Aplikasi akan berjalan di `http://localhost:8000`.

## Penggunaan

- Akses halaman utama di `http://localhost:8000`.
- Untuk menjalankan fitur [fitur yang perlu diperhatikan].

## Struktur Direktori Utama

- `app/`: Berisi logic aplikasi.
- `config/`: File konfigurasi aplikasi.
- `database/`: Migrasi dan seeder.
- `resources/`: View (Blade), CSS, dan JavaScript.
- `routes/`: Definisi rute aplikasi.
- `tests/`: Pengujian unit dan fitur.

## Kontribusi

Kami sangat terbuka untuk kontribusi! Silakan fork repositori ini dan kirimkan pull request Anda.  

Langkah kontribusi:
1. Fork repositori ini.
2. Buat branch fitur baru (`git checkout -b fitur/fitur-baru`).
3. Commit perubahan Anda (`git commit -m 'Tambahkan fitur baru'`).
4. Push ke branch (`git push origin fitur/fitur-baru`).
5. Buat pull request.

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).  

## Kontak

Jika Anda memiliki pertanyaan atau saran, silakan hubungi:  
- **Nama**: [Nama Anda]  
- **Email**: [email@example.com]  
- **GitHub**: [https://github.com/username](https://github.com/username)

