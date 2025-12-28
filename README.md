# 🛒 Simple POS (Point of Sale) System

Aplikasi Kasir berbasis Web yang ringan dan cepat, dibangun menggunakan **PHP Native (REST API)** dan **MySQL**. Aplikasi ini dirancang dengan arsitektur *decoupled* di mana Backend (API) terpisah dari Frontend (View), sehingga mudah dikembangkan lebih lanjut (misalnya diintegrasikan dengan aplikasi Android).

## 🚀 Fitur Utama

* **Autentikasi Aman**: Login sistem menggunakan PHP Session dan Password Hashing (`bcrypt`).
* **Transaksi Real-time**:
    * Pencarian produk instan (Live Search).
    * Keranjang belanja interaktif (Javascript).
    * Kalkulasi otomatis (Total & Kembalian).
* **Manajemen Stok**: Stok produk berkurang otomatis saat transaksi berhasil (menggunakan *Database Transaction* untuk mencegah data korup).
* **Cetak Struk**: Mendukung format cetak untuk **Printer Thermal** (58mm/80mm) menggunakan CSS `@media print`.
* **Dashboard Laporan**: Visualisasi omzet harian, jumlah transaksi, dan produk terlaris.

## 🛠️ Teknologi yang Digunakan

* **Backend**: PHP 8.x (PDO Driver), MySQL/MariaDB.
* **Frontend**: HTML5, Vanilla JavaScript (Fetch API), Bootstrap 5.
* **Arsitektur**: RESTful API.

## 📂 Struktur Folder

```text
pos-app/
├── api/                  # Backend Logic (REST API)
│   ├── checkout.php      # Proses transaksi & update stok
│   ├── login.php         # Autentikasi user
│   ├── products.php      # Mengambil data produk
│   └── reports.php       # Data untuk dashboard
├── config.php            # Koneksi Database
├── database.sql          # Skema Database
├── index.php             # Halaman Utama (Kasir)
├── dashboard.php         # Halaman Laporan
├── login.php             # Halaman Login
└── README.md             # Dokumentasi Proyek
