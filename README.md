# 🏥 Sistem Antrian Loket RSU Banyumanik 2

Aplikasi manajemen antrian loket berbasis web untuk RSU Banyumanik 2 (ml-rsb2). Sistem ini menangani antrian pasien di berbagai loket (Loket, CS, Apotek) serta antrian poli klinik, dilengkapi tampilan display antrian dan mode anjungan (kiosk).

---

## 📋 Daftar Isi

- [Tech Stack](#tech-stack)
- [Fitur Utama](#fitur-utama)
- [Struktur Proyek](#struktur-proyek)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi — Development Lokal (Laragon)](#instalasi--development-lokal-laragon)
- [Instalasi — Docker](#instalasi--docker)
- [Konfigurasi](#konfigurasi)
- [Daftar Model](#daftar-model)
- [Testing](#testing)
- [Menjalankan Display & Anjungan](#menjalankan-display--anjungan)

---

## Tech Stack

| Komponen | Versi / Keterangan |
|----------|--------------------|
| PHP | >= 8.2 |
| Laravel | 12.47.0 |
| Database utama | MySQL |
| Database antrian | MySQL (koneksi terpisah, lihat `config/database.php`) |
| Frontend | Blade + Vanilla JS + CSS |
| Deployment | Docker (Nginx + PHP-FPM) |
| Dev lokal | Laragon |

**Package utama:**
- Native Laravel — tidak ada package admin panel eksternal
- Konfigurasi antrian kustom via `config/antrian.php`

---

## Fitur Utama

### 🎫 Antrian Loket
- Manajemen antrian multi-loket: **Loket**, **CS**, **Apotek**
- Pemanggilan & skip antrian
- Mode **Anjungan** (kiosk self-service) untuk ambil nomor antrian
- Display antrian realtime per loket

### 🏥 Antrian Poli Klinik
- Antrian per poliklinik & dokter
- Jadwal dokter & registrasi periksa
- Data pasien & riwayat kunjungan

### 📺 Display Antrian
- Tampilan display besar untuk ruang tunggu (DISPLAY-*.bat)
- Mode display terpisah per lokasi: Loket, Loket VIP, CS, CS VIP, Apotek

### ⚙️ Pengaturan Sistem
- Konfigurasi via `mlite_settings` (database-driven)
- Multi-environment: lokal, production

---

## Struktur Proyek

```
ml-rsb2/
├── app/
│   ├── Helpers/
│   │   └── AntrianHelper.php       # Helper logika antrian (panggil, skip, generate nomor)
│   ├── Http/Controllers/           # HTTP controllers
│   ├── Models/
│   │   ├── Poli/                   # Models khusus antrian poli klinik
│   │   ├── AntrianLoket.php        # Model antrian loket utama
│   │   ├── MliteSetting.php        # Pengaturan aplikasi berbasis DB
│   │   ├── MlPasswordResetToken.php
│   │   ├── MlSession.php
│   │   └── MlUser.php              # User login sistem
│   └── Providers/
│       └── AppServiceProvider.php
├── config/
│   ├── antrian.php                 # Konfigurasi antrian (loket, prefix, dll)
│   ├── database.php                # Multi-koneksi DB (MySQL utama + DB antrian)
│   └── ...
├── database/
│   ├── migrations/
│   │   ├── poli/                   # Migrasi khusus poli klinik
│   │   └── ...
│   └── seeders/
│       ├── AntrianLoketTestSeeder.php
│       ├── MlSettingsSeeder.php
│       ├── PoliDatabaseSeeder.php
│       └── Poli*Seeder.php         # Seeder data poli (dokter, jadwal, pasien, dll)
├── docker/
│   ├── nginx/
│   │   ├── default.conf            # Nginx config lokal
│   │   └── default.prod.conf       # Nginx config production
│   └── php/
│       └── Dockerfile
├── public/
│   ├── css/ & js/                  # Asset frontend (partials)
│   ├── plugins/anjungan/           # Plugin JS untuk mode kiosk
│   └── src/                        # Gambar & aset (logo, banner, background)
├── resources/views/
│   ├── anjungan/                   # Blade views mode kiosk/anjungan
│   ├── components/                 # Blade components reusable
│   └── layouts/                    # Layout utama
├── routes/
│   ├── web.php                     # Route utama (loket, display, admin)
│   └── anjungan_poli.php           # Route khusus anjungan poli
├── zzzzzz_bat/                     # Script .bat untuk menjalankan APM & Display
│   ├── APM-Loket.bat / APM-Loket (prod).bat
│   ├── APM-CS.bat / APM-CS (prod).bat
│   ├── APM-Apotek.bat / APM-Apotek (prod).bat
│   ├── DISPLAY-Loket(prod).bat
│   ├── DISPLAY-CS(prod).bat
│   └── ...
└── COMMANDS.md                     # Referensi perintah penting proyek ini
```

---

## Persyaratan Sistem

**Development lokal:**
- PHP >= 8.2 (dengan ekstensi: `mbstring`, `pdo_mysql`, `openssl`, `tokenizer`, `xml`)
- MySQL >= 8.0
- Laragon (disarankan) atau XAMPP
- Composer >= 2.x
- Node.js >= 18.x & npm

**Production:**
- Docker & Docker Compose
- MySQL >= 8.0 (bisa eksternal/cloud)

---

## Instalasi — Development Lokal (Laragon)

```bash
# 1. Clone repository
git clone <repo-url> ml-rsb2
cd ml-rsb2

# 2. Install dependensi PHP
composer install

# 3. Install dependensi Node.js
npm install

# 4. Salin file environment
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Sesuaikan konfigurasi database di .env
#    (lihat bagian Konfigurasi di bawah)

# 7. Buat database di MySQL, lalu jalankan migrasi
php artisan migrate

# 8. Jalankan seeder pengaturan dasar
php artisan db:seed --class=MlSettingsSeeder

# 9. (Opsional) Seeder data dummy poli
php artisan db:seed --class=PoliDatabaseSeeder

# 10. Jalankan server lokal
php artisan serve
```

> 💡 **Tips Laragon:** Pastikan virtual host sudah terbuat otomatis oleh Laragon. Akses via `http://ml-rsb2.test`

---

## Instalasi — Docker

```bash
# Development
docker-compose up -d

# Production
docker-compose -f docker-compose.prod.yml up -d
```

Konfigurasi Nginx tersedia di:
- `docker/nginx/default.conf` — lokal
- `docker/nginx/default.prod.conf` — production

---

## Konfigurasi

Sesuaikan file `.env`:

```env
APP_NAME="Antrian RSB2"
APP_ENV=local
APP_URL=http://ml-rsb2.test

# Database utama (users, settings)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ml_rsb2
DB_USERNAME=root
DB_PASSWORD=

# Timezone penting untuk antrian
APP_TIMEZONE=Asia/Jakarta
```

> ⚠️ Cek `config/database.php` — aplikasi ini mungkin menggunakan **lebih dari satu koneksi database**. Pastikan semua koneksi dikonfigurasi di `.env`.

Untuk production, gunakan `.env.production` sebagai referensi:
```bash
cp .env.production .env
# sesuaikan credential production
```

---

## Daftar Model

### Models Utama

| Model | Tabel | Koneksi | Keterangan |
|-------|-------|---------|------------|
| `AntrianLoket` | `antrian_lokets` | default | Antrian loket utama (nomor, status, loket, timestamp) |
| `MliteSetting` | `mlite_settings` | default | Pengaturan aplikasi berbasis database |
| `MlUser` | `users` | default | Pengguna sistem (petugas loket, admin) |
| `MlSession` | `sessions` | default | Sesi login user |
| `MlPasswordResetToken` | `password_reset_tokens` | default | Token reset password |

### Models Poli (`app/Models/Poli/`)

| Model | Keterangan |
|-------|------------|
| Poliklinik | Data master poliklinik |
| Dokter | Data dokter per poli |
| Jadwal | Jadwal praktik dokter |
| Pasien | Data pasien |
| RegPeriksa | Registrasi kunjungan periksa |
| AntriPoli | Antrian per poli/dokter |

### Helper Utama

| File | Fungsi |
|------|--------|
| `app/Helpers/AntrianHelper.php` | Logika inti antrian: generate nomor, panggil, skip, reset harian |

---

## Testing

```bash
# Jalankan semua test
php artisan test

# Jalankan dengan output detail
php artisan test --verbose
```

> ℹ️ Test suite masih minimal (scaffold awal). Untuk data dummy, gunakan seeder:

```bash
# Seeder antrian loket (data test)
php artisan db:seed --class=AntrianLoketTestSeeder

# Seeder lengkap poli
php artisan db:seed --class=PoliDatabaseSeeder
```

---

## Menjalankan Display & Anjungan

Folder `zzzzzz_bat/` berisi script `.bat` untuk membuka browser di mode kiosk/display. Jalankan di komputer display atau anjungan:

| Script | Fungsi |
|--------|--------|
| `APM-Loket.bat` | Buka APM mode Loket (lokal) |
| `APM-Loket (prod).bat` | Buka APM mode Loket (production) |
| `APM-CS.bat` | Buka APM mode CS (lokal) |
| `APM-Apotek.bat` | Buka APM mode Apotek (lokal) |
| `DISPLAY-Loket(prod).bat` | Display antrian Loket — layar besar |
| `DISPLAY-LoketVIP(prod).bat` | Display antrian Loket VIP |
| `DISPLAY-CS(prod).bat` | Display antrian CS |
| `DISPLAY-CSVIP(prod).bat` | Display antrian CS VIP |
| `DISPLAY-Apotek(prod).bat` | Display antrian Apotek |

> 📖 Lihat `COMMANDS.md` di root proyek untuk referensi perintah-perintah penting lainnya.

---

## Kontributor

Dikembangkan untuk kebutuhan internal **RSU Banyumanik 2**, Semarang.

---

*Dokumentasi ini di-generate pada: Mei 2026*