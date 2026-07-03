# Product Requirements Document (PRD): Sistem Informasi Manajemen Reses & Aspirasi (App-Reses)

## 1. Pendahuluan
**App-Reses** adalah aplikasi berbasis web yang dirancang untuk mendigitalkan dan mengelola proses pengumpulan aspirasi masyarakat (reses) oleh anggota DPRD, serta memonitoring tindak lanjut pengerjaan fisik oleh dinas terkait (khususnya Sudin SDA DKI Jakarta). 

Aplikasi ini bertujuan untuk memastikan setiap keluhan masyarakat (seperti banjir, jalan rusak) tercatat dengan valid di lapangan, diajukan dalam rapat dewan, dan dapat dilacak progress penyelesaiannya hingga tuntas.

---

## 2. Aktor & Peran (User Roles)

1. **Super Admin / Admin Pusat**
   - Mengelola master data (Data Dewan, Fraksi, Kecamatan, Kelurahan, Kategori Pekerjaan).
   - Membuat jadwal survei lapangan / agenda reses.
   - Memonitor seluruh data aspirasi, daftar pekerjaan SDA, dan surat masuk.
2. **Petugas Lapangan (Surveyor)**
   - Menggunakan aplikasi (terutama versi mobile/responsif) saat turun ke lapangan.
   - Mengisi form survei, mengambil titik kordinat/alamat, foto kondisi lapangan, dan keluhan masyarakat.
3. **Anggota DPRD / Fraksi (Opsional/Viewer)**
   - Melihat dashboard rekapitulasi aspirasi di dapil (kecamatan/kelurahan) masing-masing.
   - Melacak sejauh mana aspirasi yang mereka bawa terealisasi oleh SKPD.
4. **SKPD / Dinas Terkait (contoh: Sudin SDA)**
   - Mengupdate *Daftar Pekerjaan* dan *Progress* (Tanggal mulai, selesai, persentase fisik).
   - Memperbarui status tindak lanjut dari surat permohonan yang masuk.

---

## 3. Fitur Utama & Modul (Key Features)

### Modul 1: Manajemen Survei & Aspirasi (Reses)
Modul ini digunakan untuk mencatat hasil temuan saat reses dewan di lapangan.
- **Admin**: Membuat *Task Survei* (Pilih Kecamatan, Nama Dewan).
- **Petugas Lapangan**: Mengisi data survei:
  - **Data Lokasi**: Kecamatan, Kelurahan, Alamat Lengkap.
  - **Data Dewan**: Nama Dewan DPRD yang menaungi.
  - **Data Aspirasi**: Detail keluhan (Banjir, Saluran Mampet, dll), Permintaan Masyarakat.
  - **Lampiran**: Foto kondisi lapangan (Geotagging jika memungkinkan).
  - **Status**: Tanggal Reses, Status Aspirasi (Baru, Disurvei, Diproses, Ditolak, Selesai).

### Modul 2: Daftar Pekerjaan (Tindak Lanjut Sudin SDA DKI Jakarta)
Modul ini untuk memonitoring pekerjaan fisik yang merupakan tindak lanjut dari aspirasi atau perencanaan dinas.
- **Form Input/Update Pekerjaan** dengan parameter lengkap:
  - *Identitas Pekerjaan*: No SKPD/UKPD, Tahun Monev, Sumber Data, Rincian Sumber Data, Kode Tracking.
  - *Identitas Pengusul*: Nama Dewan, Komisi, Pimpinan DPRD, Nama Fraksi.
  - *Lokasi*: Kecamatan, Kelurahan, RT, RW, Alamat Lengkap.
  - *Detail Pekerjaan*: Deskripsi, Lingkup Kewenangan, Kategori Pekerjaan, Kategori Prioritas, Volume/Panjang.
  - *Pelaksanaan*: Tahun Dikerjakan, Metode Pekerjaan, Checklist Perencanaan, Estimasi Tanggal Realisasi, Tanggal Mulai, Tanggal Selesai.
  - *Monitoring*: Tanggal Survei, Status Tindak Lanjut, Progress (%), Foto Dokumentasi Progress.

### Modul 3: Tracking Surat Permohonan Pemeliharaan (SDA JU)
Sistem tracking surat masuk dari instansi terbawah (RT/RW/Lurah/Camat) agar tidak hilang dan dapat dilacak.
- **Form Perekaman Surat Masuk**:
  - Tanggal Terima, Nomor Surat.
  - Pengirim (Camat, Lurah, Ketua RT/RW), Detail Pemohon.
  - Lokasi (Kecamatan, Kelurahan, Lokasi Spesifik).
  - Deskripsi Keluhan/Permohonan.
- **Tracking & Tindak Lanjut**:
  - Hasil Survei (setelah petugas turun lapangan).
  - Lampiran Foto Surat / Foto Lapangan.
  - Status Surat (Diterima, Disurvei, Dijadwalkan, Dikerjakan, Selesai).
  - Catatan tambahan.

### Modul 4: Dashboard & Pelaporan
- Menampilkan metrik: Total Aspirasi, Pekerjaan Selesai vs On Progress, Surat Masuk bulan ini.
- Grafik berdasarkan Kategori Keluhan (Banjir, Jalan), Grafik Kinerja per Kecamatan, Grafik Penyerapan/Progress SDA.

---

## 4. Rancangan Database Utama (Entity Relationship)

Untuk menunjang modul di atas, kita akan membangun beberapa tabel utama (Migration):

1. **`master_dewan`**: (id, nama, komisi, pimpinan_dprd, id_fraksi)
2. **`master_wilayah`**: Tabel Kecamatan & Kelurahan.
3. **`survei_reses`** (Modul 1):
   - id, id_dewan, id_kecamatan, alamat, keluhan, permintaan, foto, tanggal_reses, status.
4. **`pekerjaan_sda`** (Modul 2):
   - id, no_skpd, tahun_monev, sumber_data, tgl_input, kode_tracking, id_dewan, id_kelurahan, rt, rw, alamat, deskripsi, lingkup_kewenangan, kategori_pekerjaan, prioritas, status_tindak_lanjut, tgl_survei, volume, tahun_dikerjakan, metode, checklist, estimasi_realisasi, tgl_mulai, tgl_selesai, progress, foto.
5. **`surat_permohonan`** (Modul 3):
   - id, tgl_surat, no_surat, asal_surat, id_kelurahan, lokasi, detail_pemohon, deskripsi, hasil_survei, foto, status, catatan.

---

## 5. Alur Kerja (Workflow) Implementasi

Jika kita sepakat dengan PRD ini, langkah teknis yang akan saya kerjakan selanjutnya sebagai developer adalah:

1. **Tahap 1: Persiapan Database (Migration & Models)**
   - Membuat migration untuk tabel master (Dewan, Wilayah).
   - Membuat migration untuk 3 tabel inti (`survei_reses`, `pekerjaan_sda`, `surat_permohonan`).
2. **Tahap 2: Pembangunan UI/UX Admin Panel**
   - Melanjutkan layout modern yang sudah kita buat (Warna Hijau `#1F6F5F` & Krem `#F1F7D4`).
   - Membuat halaman **Master Data** (Kecamatan, Kelurahan, Anggota Dewan).
3. **Tahap 3: Modul Survei Reses (Mobile Responsive)**
   - Membuat halaman formulir yang *mobile-friendly* agar pegawai mudah menginput dari HP (Upload foto, pilih alamat).
4. **Tahap 4: Modul Pekerjaan SDA & Tracking Surat**
   - Membuat form input yang panjang dengan desain Multi-step atau Tab agar tidak membingungkan.
   - Membuat fitur pencarian berdasarkan "Kode Tracking" atau "No Surat".
5. **Tahap 5: Dashboard Analytics**
   - Mengolah data dari 3 tabel tersebut menjadi grafik dan rekapitulasi numerik.
