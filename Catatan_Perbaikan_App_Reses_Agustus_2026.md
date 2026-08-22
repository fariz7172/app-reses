# 📜 Catatan Perbaikan & Penambahan Fitur App-Reses
**Tanggal:** 22 Agustus 2026
**Dikerjakan Oleh:** Antigravity (AI Assistant) & Fariz Ahmad

Berikut adalah rangkuman seluruh pembaruan sistem, fitur baru, dan perbaikan *bug* yang telah kita kerjakan dan selesaikan selama sesi ini:

---

### 1. 🐛 Perbaikan Bug Hak Akses (Role)
* **Masalah:** Data pada halaman `Pekerjaan SDA`, `Survei Reses`, dan `Surat Permohonan` sebelumnya selalu kosong ketika *login* menggunakan akun Admin.
* **Penyebab:** Adanya perbedaan penulisan huruf besar/kecil (*case-sensitive*) antara di *database* (`admin`) dan di kode (`Admin`).
* **Solusi:** Seluruh fungsi pengecekan hak akses di dalam `Controller.php` (fungsi `getKecamatanId`) dan file lainnya telah diubah menjadi **case-insensitive** menggunakan perintah `strtolower()`. Data kini berhasil dimuat dengan sempurna untuk semua *role*.

### 2. 🕵️ Pembuatan Sistem "Activity Log" (Rekam Jejak)
* **Fungsi:** Sebuah sistem CCTV di balik layar untuk merekam seluruh aktivitas krusial pengguna (Kapan login, mengedit data apa, menambahkan surat apa, dsb).
* **UI/UX:** Dibuatkan halaman khusus yang sangat modern dengan fitur *Viewer JSON Modal* (pop-up) untuk melihat perubahan data secara detail (sebelum dan sesudah diedit).
* **Keamanan:** Halaman *Activity Log* ini dirahasiakan dan **hanya bisa diakses** oleh pengguna dengan *role* `Super Admin` dan `Admin`.

### 3. 🌐 Pembuatan Jalur API Terpusat (Data Sharing)
* **Fungsi:** Menyediakan portal data (API) berformat JSON agar aplikasi App-Reses bisa dihubungkan ke aplikasi luar/aplikasi *Mobile*.
* **Endpoint yang Tersedia:**
  - `GET /api/pekerjaan-sda/map` dan `/api/pekerjaan-sda/{id}`
  - `GET /api/surat-permohonan/map` dan `/api/surat-permohonan/{id}`
  - `GET /api/survei-reses/map` dan `/api/survei-reses/{id}`
* **Fitur Kembalian:** Mengembalikan atribut `total_semua_data` dan `total_data_dipeta` sesuai *request*.
* **Keamanan API:**
  - Dilindungi oleh sistem gembok **Laravel Sanctum**.
  - Dibatasi menggunakan *Rate Limiter* (maks 60 *request*/menit).
  - Dilengkapi perintah khusus untuk menerbitkan Token baru (`php artisan api:generate-token 1 --name="Token-MobileApp"`).

### 4. 💾 Fitur Unduh Backup Database
* **Fungsi:** Fitur 1-klik untuk mengunduh seluruh isi *database* `.sql` langsung ke laptop/komputer.
* **Metode:** Menggunakan *library* `ifsnop/mysqldump-php` yang menjamin 100% kompatibilitas pengunduhan (*Pure PHP*) tanpa bergantung pada aplikasi terminal di server maupun lokal.
* **Keamanan:** 
  - Tombol pengunduhan diletakkan di *sidebar* dan **hanya** muncul untuk `Super Admin`, `Admin`, dan `Sudin`.
  - Berkas *backup* yang tercipta di dalam server akan memusnahkan dirinya sendiri secara otomatis (*auto-delete*) segera setelah berhasil terkirim ke *browser* Anda agar *storage* server tidak penuh.

### 5. 🚀 Otomatisasi Deployment Server (SSH)
* **Masalah:** Proses *push* dari lokal ke GitHub tidak otomatis menarik/memperbarui kode di server *live*.
* **Solusi:** Dibuatkan satu buah *script* Python `deploy_to_server.py`. *Script* ini bertugas untuk masuk ke server Anda via SSH, mengambil (*pull*) pembaruan terbaru, menginstal pembaruan *library*, dan menjalankan migrasi secara otomatis, aman, dan tanpa menimpa *database* asli.


---
*Catatan ini disimpan untuk mempermudah Anda mengingat perubahan struktural yang terjadi di dalam aplikasi ini.*
