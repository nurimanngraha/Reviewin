# 🚀 Panduan Hosting CreTech (Reviewin) di InfinityFree via GitHub

Panduan ini menjelaskan langkah demi langkah cara melakukan hosting aplikasi Laravel **CreTech** ke **InfinityFree** dengan domain **`https://cretech.wuaze.com`** secara otomatis menggunakan **GitHub Actions**.

---

## 📌 Ringkasan Alur Kerja

Dengan sistem CI/CD GitHub Actions yang telah dibuat:
1. Anda melakukan `git push origin main` ke repositori GitHub.
2. Server GitHub Actions akan otomatis mengunduh dependency (`composer install --no-dev`), menyiapkan cache, dan mengunggah (*deploy*) seluruh file ke folder `htdocs/` di InfinityFree via FTP.
3. Anda tidak perlu lagi mengunggah ribuan file satu per satu lewat FileZilla!

---

## 🛠️ Langkah 1: Ambil Data Akun FTP di InfinityFree

1. Buka dan login ke dashboard InfinityFree: [https://dash.infinityfree.com](https://dash.infinityfree.com)
2. Klik akun hosting Anda yang terhubung dengan domain `cretech.wuaze.com`.
3. Di halaman akun (bagian **FTP Details**), catat 3 data berikut:
   * **FTP Hostname**: (biasanya `ftpupload.net`)
   * **FTP Username**: (contoh: `if0_38xxxxxx`)
   * **FTP Password**: (klik tombol *Show* atau gunakan password akun hosting/vPanel Anda)

---

## 🔑 Langkah 2: Masukkan Kredensial FTP ke GitHub Secrets

1. Buka repositori GitHub Anda: [https://github.com/nurimanngraha/Reviewin](https://github.com/nurimanngraha/Reviewin)
2. Klik tab **Settings** (di menu atas repositori).
3. Di bilah menu kiri, cari bagian **Security** -> klik **Secrets and variables** -> pilih **Actions**.
4. Klik tombol hijau **New repository secret**, lalu tambahkan 3 secret berikut:

| Secret Name | Value / Isi | Keterangan |
| :--- | :--- | :--- |
| `FTP_SERVER` | `ftpupload.net` | Hostname FTP dari InfinityFree |
| `FTP_USERNAME` | `if0_38xxxxxx` | Username FTP akun Anda |
| `FTP_PASSWORD` | *(Password Anda)* | Password FTP / vPanel akun Anda |

---

## 🗄️ Langkah 3: Buat Database MySQL di InfinityFree

1. Di dashboard akun InfinityFree, klik tombol **Control Panel** (vPanel).
2. Cari dan klik menu **MySQL Databases**.
3. Di bagian *Create a New Database*, masukkan nama database: `reviewin` lalu klik **Create Database**.
4. Di tabel database yang sudah dibuat, catat:
   * **MySQL Hostname**: (contoh: `sql100.infinityfree.com` atau `sql305.infinityfree.com`)
   * **MySQL Database Name**: (contoh: `if0_38xxxxxx_reviewin`)
   * **MySQL Username**: (sama dengan username vPanel Anda, misal `if0_38xxxxxx`)
   * **MySQL Password**: (sama dengan password vPanel Anda)

---

## ⚙️ Langkah 4: Buat File `.env` di InfinityFree

File `.env` berisi kunci rahasia dan koneksi database, sehingga **tidak boleh di-push ke publik GitHub**. Anda cukup membuatnya satu kali di InfinityFree:

1. Di dashboard akun InfinityFree, klik **Online File Manager** (Monsta FTP).
2. Masuk ke folder **`htdocs`**.
3. Klik tombol **New File** di toolbar atas, beri nama: **`.env`**
4. Klik file `.env` tersebut lalu klik **Edit**.
5. Salin dan tempel konfigurasi di bawah ini (sesuaikan data database dari Langkah 3):

```env
APP_NAME="CreTech"
APP_ENV=production
APP_KEY=base64:ODjZvJiyduj5SE6N92tYZ6LAlM/ZFT9qus2FJIGKPfw=
APP_DEBUG=false
APP_URL=https://cretech.wuaze.com

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID

APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=error

# Sesuaikan dengan data MySQL dari Langkah 3:
DB_CONNECTION=mysql
DB_HOST=sqlxxx.infinityfree.com
DB_PORT=3306
DB_DATABASE=if0_38xxxxxx_reviewin
DB_USERNAME=if0_38xxxxxx
DB_PASSWORD=password_vpanel_anda

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
CACHE_STORE=file
```
6. Klik **Save & Close**.

---

## ⚡ Langkah 5: Jalankan Migrasi & Data Awal Database

Pilih salah satu dari 2 cara mudah berikut:

### Cara A (Paling Cepat - 1 Detik via Browser)
Setelah deploy pertama selesai, buka URL ini di browser Anda:
```text
https://cretech.wuaze.com/system/migrate?key=fa66903742b9e12b
```
Laravel akan otomatis membuat semua tabel dan mengisikan data default! Layar hijau sukses akan muncul.

### Cara B (Melalui phpMyAdmin di vPanel)
1. Buka **Control Panel** InfinityFree -> klik **phpMyAdmin**.
2. Klik tombol **Connect** di sebelah nama database Anda.
3. Klik tab **Import** di bagian atas.
4. Klik **Choose File** -> pilih file `database/infinityfree_setup.sql` yang ada di proyek ini.
5. Gulir ke bawah lalu klik **Import / Kirim**. Selesai!

---

## 🚀 Langkah 6: Kirim Perubahan ke GitHub untuk Auto-Deploy

Jalankan perintah ini di terminal VSCode/Antigravity Anda:

```bash
git add .
git commit -m "Setup InfinityFree deployment via GitHub Actions"
git push origin main
```

Pantau prosesnya di tab **Actions**: [https://github.com/nurimanngraha/Reviewin/actions](https://github.com/nurimanngraha/Reviewin/actions).
Begitu statusnya centang hijau (Success), aplikasi Anda sudah langsung live di **https://cretech.wuaze.com**!

---

## 👤 Akun Login Administrator (Produksi)
* **Email Utama**: `admin@cretech.com`
* **Password Utama**: `admincretech2026`
* **Email Cadangan**: `admin@reviewin.test`
* **Password Cadangan**: `password`

> **Catatan Reset Database:**
> Untuk melakukan reset bersih database dan membuat akun admin di atas kapan saja, cukup buka:
> `https://cretech.wuaze.com/system/migrate?key=fa66903742b9e12b&fresh=1`

---

## 🔐 Cara Reset Password Admin / Daftar Admin Baru (URL Rahasia)

Sesuai standar keamanan, **tidak ada tombol publik "Daftar sebagai Admin"** di website agar pengunjung umum dan pelanggan tidak dapat sembarangan mendaftar sebagai Administrator.

Jika Anda **lupa password admin** atau ingin **mendaftarkan akun admin baru**, Anda dapat menggunakan salah satu dari 2 cara berikut:

### 1. Menggunakan URL Rahasia (Paling Mudah, Cepat & Aman)
Buka tautan rahasia ini langsung di browser Anda:
```text
https://cretech.wuaze.com/system/admin-setup?key=fa66903742b9e12b
```
*(Atau bisa juga menggunakan parameter cadangan: `key=cretechadmin2026`)*

* **Jika Lupa Password:** Ketikkan email admin Anda (atau klik nama admin dari daftar yang tersedia di halaman tersebut), lalu masukkan password baru -> Klik **Simpan Akun / Reset Password Admin**. Password langsung diperbarui!
* **Jika Ingin Menambah Admin Baru:** Masukkan nama lengkap, email baru, dan password baru -> Akun baru otomatis aktif dengan role `admin`.
* Di halaman ini juga ditampilkan **Daftar Akun Admin Terdaftar** sehingga Anda dapat melihat semua email admin yang ada jika sewaktu-waktu lupa emailnya.

### 2. Melalui phpMyAdmin di InfinityFree Control Panel (Cara Alternatif Database)
1. Buka **Control Panel** InfinityFree -> klik **phpMyAdmin** -> klik **Connect** pada database Anda.
2. Klik tabel `users` di panel sebelah kiri.
3. Cari baris akun admin Anda, lalu klik tombol **Edit**.
4. Pada kolom `password`:
   * Pada dropdown **Function**, pilih **MD5**.
   * Pada kolom **Value**, ketikkan password baru Anda.
   * Klik **Go / Kirim** di kanan bawah.
*(Catatan: Cara nomor 1 via URL Rahasia jauh lebih disarankan karena otomatis menggunakan hashing Bcrypt resmi Laravel).*

