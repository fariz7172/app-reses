# Product Requirements Document (PRD) - Security Patch & Hardening
**Aplikasi:** Sistem Informasi Manajemen Reses & Aspirasi (App-Reses)  
**Dokumen:** Security Assessment & Mitigation Plan

## 1. Pendahuluan
Dokumen ini menguraikan hasil audit keamanan (*security assessment*) secara menyeluruh terhadap aplikasi App-Reses dan pembaruan (*patch*) yang telah diimplementasikan untuk menutup celah-celah keamanan tersebut. Langkah ini dilakukan untuk memastikan data pemerintahan dan operasional Dinas SDA aman dari manipulasi pihak yang tidak bertanggung jawab, peretasan, dan kebocoran data.

---

## 2. Temuan Keamanan & Tindakan Mitigasi yang Telah Diterapkan

### A. Broken Access Control & Privilege Escalation (Eskalasi Hak Akses)
- **Risiko (Sebelumnya):** Manajemen *User* (Pengguna) hanya difilter agar tidak bisa diakses oleh role "Kecamatan". Hal ini membuka celah di mana *role* lain seperti "Vendor" atau "Pelaksana" dapat masuk ke halaman manajemen *user* dan menciptakan akun Super Admin baru untuk diri mereka sendiri (Pengambilalihan sistem).
- **Mitigasi (Selesai):** Telah diimplementasikan sistem **Gates Otorisasi Terpusat**. Kini halaman Manajemen Pengguna secara ketat HANYA dapat diakses oleh *role* **Super Admin**.

### B. Insecure Direct Object Reference (IDOR) & Isolasi Data Wilayah
- **Risiko (Sebelumnya):** Logika filter data per Kecamatan memiliki *bug* pada metode otorisasi wilayah. Pengguna dengan *role* yang tidak terdefinisi (seperti Vendor) akan lolos dari filter kecamatan (mendapatkan nilai `null` alih-alih ID invalid), yang membuat sistem menganggap mereka sebagai Super Admin yang berhak melihat, mengedit, dan menghapus seluruh Pekerjaan SDA dan Surat Permohonan.
- **Mitigasi (Selesai):** Logika identifikasi wilayah telah diperbaiki. Kini, sistem secara eksplisit akan memblokir (*return -1*) semua akun yang bukan Super Admin / Sudin / Kecamatan resmi. Sehingga peretas tidak dapat melihat atau memanipulasi data lintas batas otorisasi.

### C. Pencegahan Destruksi Data (Data Deletion Protection)
- **Risiko (Sebelumnya):** Semua level akun (*Kecamatan*, dll) dapat mengeksekusi perintah HAPUS (*delete*) secara permanen pada data krusial seperti Surat Permohonan dan Pekerjaan SDA.
- **Mitigasi (Selesai):** Otorisasi penghapusan data kini hanya dilimpahkan kepada **Super Admin** dan **Sudin**. Tingkat wilayah (Kecamatan) dan pihak luar tidak lagi memiliki izin untuk menghilangkan riwayat pekerjaan secara permanen dari sistem.

### D. Pencegahan Serangan Cross-Site Scripting (XSS) via File Upload
- **Risiko (Sebelumnya):** Form unggah dokumen/foto menerima semua format "image". Mesin sistem secara *default* mendeteksi `.svg` sebagai bagian dari "image". File SVG rentan disusupi *script* jahat (Malware / JavaScript) yang dapat mencuri sesi/cookie dari pengguna admin yang membukanya (Stored XSS).
- **Mitigasi (Selesai):** Sistem validasi file telah diubah menggunakan metode `mimes`. Saat ini sistem hanya akan menerima foto berformat **JPG, JPEG, PNG, dan WEBP** dengan kapasitas dibatasi secara ketat maksimal **20MB** per *file*. Ekstensi SVG dan GIF otomatis diblokir.

### E. Pencegahan Serangan Brute Force (Keamanan Login)
- **Risiko (Sebelumnya):** Halaman *login* administrator belum memiliki pelindung laju (*rate limiting*). *Hacker* atau *Bot* dapat melakukan serangan *Brute Force* atau *Credential Stuffing* dengan mencoba jutaan kombinasi *password* berturut-turut tanpa henti hingga berhasil membobol akun.
- **Mitigasi (Selesai):** Telah dipasangkan sensor *Throttle* (Rate Limiter) pada rute validasi sandi. Jika ada aktivitas login gagal secara tidak wajar melebihi **5 kali percobaan dalam 1 menit**, sistem akan otomatis memblokir IP/User tersebut secara temporer (Status Code: 429 Too Many Requests).

---

## 3. Kesimpulan
Dengan diimplementasikannya lima lapis keamanan (*Security Patches*) di atas—mulai dari *Rate Limiting* di gerbang masuk (Login), perlindungan injeksi file (XSS), isolasi data wilayah (IDOR), hingga hierarki privilese level tinggi (*Gates Control*)—aplikasi App-Reses kini telah memenuhi standar kepatuhan keamanan *cyber* (Cybersecurity Compliance) yang lebih tinggi untuk level sistem informasi birokrasi dan kedinasan.
