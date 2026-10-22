# Product Requirements Document (PRD) & Release Notes: API Integration & GIS Map Update
**Aplikasi:** Sistem Informasi Manajemen Reses & Aspirasi (App-Reses)  
**Tanggal Rilis:** 18 - 22 Oktober 2026  
**Versi:** 2.1.1 (RESTful API & GIS Map Inventory Integration)  
**Penulis:** Tim Pengembang App-Reses  

---

## 1. Latar Belakang & Tujuan (Background & Objectives)

Seiring kebutuhan integrasi dengan aplikasi eksternal (seperti **Aplikasi Mobile**, aplikasi **Inventory SDA JU**, dan sistem pihak ketiga), App-Reses membutuhkan API mandiri yang tangguh, aman, dan berstandar RESTful, serta visualisasi pemetaan GIS yang membedakan sumber data pekerjaan secara akurat.

### Tujuan Utama:
1. **Ketersediaan Layanan API Mandiri (Headless/API-First):** Memungkinkan aplikasi mobile atau klien pihak ketiga melakukan operasi penuh (**Create, Read, Update**) terhadap data aspirasi dan pekerjaan fisik tanpa melewati antarmuka web.
2. **Efisiensi & Optimasi Storage:** Mengurangi ukuran file foto lapangan melalui konversi otomatis gambar ke format **WebP** dengan kualitas seimbang.
3. **Fleksibilitas Data Lapangan:** Mendukung aspirasi langsung masyarakat atau survei internal yang tidak selalu terikat dengan Anggota Dewan (`id_dewan` kini opsional).
4. **Resiliensi & Standardisasi Error:** Meniadakan crash halaman HTML 500 Internal Server Error saat request API gagal, menggantinya dengan respons JSON HTTP 422/404/500 yang ramah developer.
5. **Diferensiasi Visual GIS (Peta Lokasi):** Membedakan marker pekerjaan fisik yang bersumber dari aplikasi **Inventory** dengan warna khusus (**Pink**) dan memperbarui panel legenda peta.

---

## 2. Ruang Lingkup Perubahan (Scope of Changes)

Pembaruan pada 18 - 22 Oktober 2026 mencakup:

| Area | Sebelum Update | Setelah Update (v2.1.1) |
|---|---|---|
| **CRUD Pekerjaan SDA** | Hanya `GET /map` & `GET /{id}` | Lengkap: `GET List (Pagination)`, `GET /map`, `GET /{id}`, `POST`, `PUT/POST Update`, `PATCH Progress` |
| **CRUD Survei Reses** | Hanya `GET /map` & `GET /{id}` | Lengkap: `GET List (Pagination)`, `GET /map`, `GET /{id}`, `POST`, `PUT/POST Update` |
| **Unggah Foto** | Menyimpan format asli (JPG/PNG) | Otomatis dikonversi ke **`.webp`** (kualitas 82) & folder terisolasi |
| **Aturan `id_dewan`** | Wajib (`NOT NULL` di database) | **Opsional / Boleh kosong** di database & validasi controller |
| **Handling Input Kosong** | String kosong `""` memicu error validasi foreign key | Normalisasi otomatis: string `""` dikonversi ke `null` |
| **Pencegahan Error Duplikat** | Crash 500 jika `kode_tracking` duplikat | Validasi Laravel `unique` + fallback tangkap exception (HTTP 422) |
| **Visualisasi GIS Map** | Hanya marker Hijau (selesai), Orange (reses), Biru (umum) | **Marker Pink (`#ec4899`)** khusus data `sumber_data = Inventory / Iventory` + Legenda Pink |
| **Filter & Counter Peta** | Filter hanya Masyarakat & Reses | Tambah opsi filter **Inventory** + counter `<span id="inventory-count">` |
| **Koleksi Postman** | Belum tersedia | Disediakan file **`App-Reses-API.postman_collection.json`** siap pakai |

---

## 3. Spesifikasi Teknis Endpoint API

Semua endpoint dilindungi oleh **Laravel Sanctum Token** dan **Rate Limiter (60 req/menit)**:
- **Base URL Lokal:** `http://127.0.0.1:1111/api`
- **Base URL Server:** `https://app-reses.farizahmad.com/api`
- **Header Wajib:**
  ```http
  Authorization: Bearer {token}
  Accept: application/json
  ```

---

### A. Modul Pekerjaan SDA (`/api/pekerjaan-sda`)

#### 1. `GET /api/pekerjaan-sda` (Daftar Seluruh Data)
- **Fungsi:** Mengambil daftar pekerjaan SDA dengan pagination dan filter dinamis.
- **Query Parameter (Opsional):**
  - `per_page` (int, default: 15, max: 100)
  - `search` (string): Mencari pada alamat, deskripsi, nomor SKPD, dan kode tracking.
  - `sumber_data` (string): Filter sumber (`Reses`, `Masyarakat`, `Musrenbang`, dll).
  - `id_kecamatan` (int): Filter berdasarkan ID Kecamatan.
  - `id_kelurahan` (int): Filter berdasarkan ID Kelurahan.
  - `tahun_monev` (int): Filter tahun anggaran / monev.
  - `progress` (string): Filter status pengerjaan (`100`, `<100`, `0`).

#### 2. `GET /api/pekerjaan-sda/map` (Data GIS Peta)
- **Fungsi:** Mengambil seluruh pekerjaan yang memiliki titik koordinat GPS (`latitude` & `longitude`).

#### 3. `GET /api/pekerjaan-sda/{id}` (Detail Pekerjaan)
- **Fungsi:** Mengambil detail 1 record beserta relasi dewan, kecamatan, kelurahan, pelaksana, vendor, dan surat permohonan.

#### 4. `POST /api/pekerjaan-sda` (Tambah Pekerjaan Baru)
- **Content-Type:** `multipart/form-data` atau `application/json`
- **Parameter Wajib:** `sumber_data`, `id_kecamatan`, `alamat`, `deskripsi`.
- **Parameter Opsional:** `id_dewan` *(boleh kosong)*, `id_kelurahan`, `id_pelaksana`, `no_skpd`, `tahun_monev`, `tgl_input`, `kode_tracking`, `rt`, `rw`, `lingkup_kewenangan`, `kategori_pekerjaan`, `kategori_prioritas`, `volume_panjang`, `metode_pekerjaan`, `tgl_mulai`, `tgl_selesai`, `progress`, `latitude`, `longitude`, `photo[]`.

#### 5. `PUT` / `POST /api/pekerjaan-sda/{id}` (Update Pekerjaan)
- **Fungsi:** Memperbarui data pekerjaan yang ada.
- **Fitur Tambahan:**
  - Mendukung upload foto tambahan tanpa menghapus foto lama.
  - Mendukung penghapusan foto spesifik lewat parameter `hapus_foto[]`.
  - Otomatis menyinkronkan data pembaruan ke tabel `surat_permohonan`.

#### 6. `PATCH /api/pekerjaan-sda/{id}/progress` (Update Cepat Progress)
- **Fungsi:** Endpoint khusus untuk memperbarui persentase pengerjaan fisik (`0 - 100%`) dan status tindak lanjut.

---

### B. Modul Survei Reses (`/api/survei-reses`)

#### 1. `GET /api/survei-reses` (Daftar Seluruh Data)
- **Fungsi:** Mengambil data survei aspirasi dengan pagination dan pencarian.
- **Query Parameter (Opsional):**
  - `per_page` (int)
  - `search` (string): Mencari pada alamat, keluhan, permintaan, dan keterangan.
  - `status` (string): `Menunggu`, `Diproses`, `Selesai`.
  - `id_dewan` (int): Filter berdasarkan anggota dewan.
  - `id_kecamatan` (int): Filter berdasarkan kecamatan.

#### 2. `GET /api/survei-reses/map` (Data Peta Aspirasi)
- **Fungsi:** Mengambil ringkasan survei reses untuk plotting peta wilayah.

#### 3. `GET /api/survei-reses/{id}` (Detail Survei)
- **Fungsi:** Mengambil detail aspirasi dewan beserta relasi pekerjaan fisik SDA terkait.

#### 4. `POST /api/survei-reses` (Input Aspirasi Reses Baru)
- **Content-Type:** `multipart/form-data`
- **Parameter Wajib:** `id_kecamatan`, `id_kelurahan`, `tanggal_reses`, `alamat`, `permintaan`.
- **Parameter Opsional:** `id_dewan` *(boleh kosong)*, `keterangan`, `status`, `panjang`, `lebar`, `tinggi`, `volume`, `estimasi_biaya`, `foto[]`.
- **Otomasi Terintegrasi:** Menyimpan data survei reses sekaligus **otomatis membuat Pekerjaan SDA dan Surat Permohonan**.

#### 5. `PUT` / `POST /api/survei-reses/{id}` (Update Aspirasi Reses)
- **Fungsi:** Mengubah data survei aspirasi.
- **Otomasi Sinkronisasi:** Otomatis memperbarui data deskripsi, alamat, dan dewan pada `pekerjaan_sdas` dan `surat_permohonans` terkait.

---

## 4. Perubahan Database & Migrasi (Database Schema Updates)

Dilakukan migrasi database untuk mengubah kolom `id_dewan` pada tabel `survei_reses`:

```php
// database/migrations/2026_10_18_100449_make_id_dewan_nullable_in_survei_reses_table.php
Schema::table('survei_reses', function (Blueprint $table) {
    $table->unsignedBigInteger('id_dewan')->nullable()->change();
});
```

- **Tabel `survei_reses`:** Kolom `id_dewan` kini bertipe `BIGINT UNSIGNED NULL` (sebelumnya `NOT NULL`).
- **Tabel `pekerjaan_sdas`:** Kolom `id_dewan` tetap `BIGINT UNSIGNED NULL`.

---

## 5. Keamanan & Konversi Aset Foto (Image Engine)

Untuk mengoptimalkan kuota bandwidth server dan mempercepat loading aplikasi mobile/web:
- Digunakan PHP GD library untuk mengonversi gambar format `JPG/JPEG`, `PNG`, dan `WebP` menjadi format terkompresi **`.webp`**.
- Quality setting: **82%** (kualitas visual tetap tajam dengan reduksi ukuran berkas hingga 60-80%).
- Penanganan transparansi PNG (`imagealphablending` & `imagesavealpha`).
- Nama berkas di-generate menggunakan `UUID v4` unik untuk menghindari tabrakan nama file.

## 6. Visualisasi GIS & Marker Khusus Inventory (Update 22 Oktober 2026)

Pada modul Peta Sebaran Pekerjaan SDA (`/admin/pekerjaan-sda/map`), ditambahkan diferensiasi visual untuk mempermudah identifikasi pekerjaan yang bersumber dari aplikasi **Inventory**:

1. **Custom Marker Pink:**
   - Dibuatkan ikon pin Leaflet khusus berbasis SVG inline data URI dengan warna **Pink cerah (`#ec4899`)** dan border merah muda tua (`#be185d`).
   - Setiap data `PekerjaanSda` dengan nilai `sumber_data = Inventory` (atau `Iventory`, case-insensitive) otomatis dirender menggunakan marker pink.
2. **Pembaruan Panel Legenda:**
   - Menambahkan indikator dot warna pink bertuliskan **Inventory** pada panel legenda di pojok kanan bawah peta:
     - 🟣 Pink (`#ec4899`): **Inventory**
     - 🟠 Orange (`#f97316`): **Reses**
     - 🔵 Biru (`#3b82f6`): **Usulan Masyarakat**
     - 🟢 Hijau (`#10b981`): **Pekerjaan Selesai**
3. **Filter & Counter Interaktif:**
   - Opsi `Inventory` ditambahkan pada dropdown filter *Sumber Data*.
   - Counter jumlah pekerjaan Inventory ditampilkan di baris ringkasan judul peta: `<span id="inventory-count">0</span> Inventory`.
   - Controller `PekerjaanSdaController::mapView` diperbarui untuk mendukung filter query `?sumber_data=Inventory` secara case-insensitive.

---

## 7. Verifikasi & Deployment Server

Proses perilisan telah berhasil dilakukan ke server produksi:
1. **Repository GitHub:** Branch `farizahmad.github.io` commit `691308a` (API update) dan commit `d7e1d13` (GIS Map update).
2. **Server Host:** `145.79.14.233` (`app-reses.farizahmad.com`).
3. **Database Integrity:**
   - Database server **100% aman** (hanya menjalankan migrasi skema tabel, tidak menimpa data yang ada).
   - Seluruh data lokal tetap berada di lingkungan lokal.
4. **Status Operasional:** Server berjalan normal dengan HTTP Response `200 OK`.

---

## 8. Referensi Berkas Terkait

- **Peta Lokasi Blade:** [`resources/views/admin/pekerjaan-sda/map.blade.php`](file:///d:/program%20file/Project%20Kantor/App-Reses/resources/views/admin/pekerjaan-sda/map.blade.php)
- **Controller Pekerjaan SDA:** [`app/Http/Controllers/Admin/PekerjaanSdaController.php`](file:///d:/program%20file/Project%20Kantor/App-Reses/app/Http/Controllers/Admin/PekerjaanSdaController.php)
- **Postman Collection:** [`App-Reses-API.postman_collection.json`](file:///d:/program%20file/Project%20Kantor/App-Reses/App-Reses-API.postman_collection.json)
- **API Controller Pekerjaan SDA:** [`PekerjaanSdaApiController.php`](file:///d:/program%20file/Project%20Kantor/App-Reses/app/Http/Controllers/Api/PekerjaanSdaApiController.php)
- **API Controller Survei Reses:** [`SurveiResesApiController.php`](file:///d:/program%20file/Project%20Kantor/App-Reses/app/Http/Controllers/Api/SurveiResesApiController.php)
- **Route Definition:** [`routes/api.php`](file:///d:/program%20file/Project%20Kantor/App-Reses/routes/api.php)
- **Deployment Script:** [`deploy_to_server.py`](file:///d:/program%20file/Project%20Kantor/App-Reses/deploy_to_server.py)
