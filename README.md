# ReviewIn — Sistem Pengelolaan QR Code & NFC Google Review

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12" />
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS" />
  <img src="https://img.shields.io/badge/MySQL-8.4-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/Tests-10%20Passed-success?style=for-the-badge" alt="Tests" />
</p>

ReviewIn adalah platform web komprehensif untuk penyediaan, manajemen, dan aktivasi perangkat **QR Code** dan kartu **NFC** Google Review bagi berbagai jenis bisnis/outlet. Sistem dilengkapi dengan arsitektur role-based (**Administrator** dan **Pemilik Bisnis / Business Owner**), sistem telemetry otomatis (IP, User Agent, Platform, QR vs NFC), serta aktivasi mandiri saat perangkat pertama kali di-scan/tap.

---

## 🚀 Fitur Utama

### 1. Administrator
- **Penyediaan Perangkat (Provisioning)**:
  - Pembuatan perangkat satuan dengan kode unik otomatis (format `REV-XXXXXX`) atau kode kustom.
  - Pembuatan perangkat massal / batch (*bulk provisioning*) dengan kustomisasi prefix dan jumlah.
  - Dukungan tipe perangkat: `qr_code`, `nfc_card`, dan `qr_nfc`.
- **Manajemen Status**:
  - `unactivated`: Perangkat baru belum diikat ke bisnis.
  - `active`: Perangkat terhubung ke bisnis dan aktif mengarahkan pelanggan.
  - `inactive`: Perangkat dinonaktifkan sementara.
  - `blocked`: Perangkat diblokir demi keamanan.
- **Fitur Reset Perangkat**: Melepas tautan perangkat dari bisnis dan mengembalikannya ke status `unactivated` untuk digunakan kembali.
- **Ekspor QR Code**: Unduh QR Code dalam format vektor **SVG** (cetak resolusi tinggi) dan raster **PNG**.
- **Manajemen Bisnis & Pengguna**: CRUD akun pemilik bisnis dan data profil outlet lengkap beserta Google Review URL.
- **Monitoring & Telemetri**: Rekap log pemindaian lengkap (IP, User Agent, Platform OS, Tipe Perangkat, Browser, Waktu).

### 2. Pemilik Bisnis (Business Owner)
- **Aktivasi Mandiri Pertama Kali**:
  - Saat perangkat fisik baru di-scan atau di-tap pertama kali, sistem otomatis mengarahkan ke halaman aktivasi `/activate/{device_code}`.
  - Pemilik bisnis dapat memilih bisnis yang sudah terdaftar atau membuat bisnis baru langsung di formulir aktivasi.
  - Mengisi / memperbarui tautan Google Review bisnis.
- **Portal Pemilik Bisnis (`/portal/dashboard`)**:
  - Ringkasan statistik performa pemindaian perangkat miliknya.
  - Manajemen daftar perangkat aktif.
  - Pengaturan Link Google Review (`/portal/settings`).
  - Analitik visual interaktif (`/portal/analytics`).

### 3. Pelanggan & Redirect Cerdas (`/r/{device_code}`)
- Pelanggan memindai QR atau menempelkan NFC ke `/r/{device_code}` (atau `/r/{device_code}?t=nfc`).
- **Tanpa Login**: Sistem langsung mencatat log telemetri di database dan melakukan **HTTP 302 Redirect** seketika ke halaman review Google bisnis.
- Penanganan cerdas:
  - Status `unactivated` &rarr; Redirect ke `/activate/{device_code}`.
  - Status `inactive` atau `blocked` &rarr; Menampilkan halaman informasi 403.
  - Kode tidak terdaftar &rarr; Menampilkan halaman 404 elegan.

---

## 🛠️ Tech Stack & Arsitektur

- **Backend**: Laravel 12 (PHP 8.2)
- **Database**: MySQL 8.x
- **Frontend**: Blade Templating, Tailwind CSS, Alpine.js, Chart.js, FontAwesome Icons
- **Libraries**:
  - `simplesoftwareio/simple-qrcode`: Generator QR Code SVG & PNG
- **Arsitektur Service Layer**:
  - `DeviceActivationService`: Alur validasi aktivasi & reset perangkat
  - `DeviceRedirectService`: Alur redirect cerdas & status checking
  - `ScanTrackingService`: Parser User Agent, pembedaan QR vs NFC & pencatatan log
  - `QrCodeService`: Rendering SVG & konversi download PNG

---

## ⚡ Instalasi & Menjalankan Lokal

### Prasyarat
- PHP 8.2 atau lebih baru (ekstensi `pdo_mysql`, `gd`, `openssl`, `mbstring`)
- Composer
- MySQL Server (misal Laragon atau XAMPP)

### Langkah-langkah
```bash
# 1. Clone repository
git clone https://github.com/nurimanngraha/Reviewin.git
cd Reviewin

# 2. Install dependencies PHP
composer install

# 3. Salin environment file
cp .env.example .env

# 4. Generate Application Key
php artisan key:generate

# 5. Konfigurasi database di .env (pastikan database 'reviewin' sudah dibuat di MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reviewin
DB_USERNAME=root
DB_PASSWORD=

# 6. Jalankan migrasi dan seeder demo data
php artisan migrate --seed

# 7. Jalankan local development server
php artisan serve
```

Aplikasi dapat diakses di: **http://127.0.0.1:8000**

---

## 🔑 Kredensial Demo

| Role | Email | Password | Akses |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@reviewin.test` | `password` | `/admin/dashboard` |
| **Business Owner** | `owner@reviewin.test` | `password` | `/portal/dashboard` |

*Tersedia tombol autofill **Demo Admin** dan **Demo Owner** pada halaman `/login`.*

---

## 🧪 Pengujian Fitur (Feature Tests)

Semua skenario alur kerja sistem telah diuji menggunakan PHPUnit:

```bash
php artisan test --filter=DeviceWorkflowTest
```

```text
   PASS  Tests\Feature\DeviceWorkflowTest
  ✓ unactivated device redirects to activation page
  ✓ active device records telemetry and redirects to google review
  ✓ active device nfc tap records nfc telemetry
  ✓ inactive and blocked devices show notice page
  ✓ unknown device shows 404 page
  ✓ business owner can activate unactivated device
  ✓ role based access control
  ✓ admin can download qr svg and png
  ✓ admin can bulk create devices
  ✓ admin can reset active device

  Tests:    10 passed (56 assertions)
```

---

## 📄 Lisensi
ReviewIn dilisensikan di bawah [MIT License](LICENSE).
