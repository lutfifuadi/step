# 📊 Analisis SWOT & Strategi Optimasi

Dokumen ini berisi analisis SWOT (Strengths, Weaknesses, Opportunities, Threats) untuk **Aplikasi Step** guna memetakan langkah optimasi sistem secara komprehensif.

---

## 1. 🛡️ Keamanan Data (Enkripsi & Moderasi Risiko)

### **Strengths (Kekuatan)**
* **Proteksi Identitas:** Mengenkripsi nama asli remaja (`real_name`) dengan kunci enkripsi dua arah sebelum disimpan ke database untuk menjaga anonimitas.
* **Filter Krisis Mandiri:** Memiliki `ExpressionModerationService` otomatis yang menandai kata kunci sensitif (seperti self-harm atau bunuh diri) sebelum divalidasi admin.
* **Kontrol Ganda:** Menggabungkan filter sistem dengan dashboard persetujuan admin sebelum data ditayangkan ke peneliti atau publik.

### **Weaknesses (Kelemahan)**
* **Deteksi Kaku:** Moderasi otomatis saat ini masih berbasis kata kunci mentah (*rule-based*), berisiko tinggi memunculkan kesalahan klasifikasi (*false positive* / *false negative*).
* **Ketiadaan Audit Trail:** Belum ada log aktivitas rinci yang merekam saat admin melakukan dekripsi identitas atau mengubah status ekspresi.

### **Opportunities (Peluang)**
* **Integrasi AI/NLP:** Memanfaatkan API pihak ketiga (misalnya OpenAI Moderation API atau model klasifikasi bahasa lokal) untuk mendeteksi konteks emosi secara lebih natural.
* **Sistem Respon Cepat:** Memicu notifikasi otomatis langsung ke email/WhatsApp konselor jika sistem mendeteksi tingkat risiko krisis yang ekstrem.

### **Threats (Ancaman)**
* **Serangan Bypass:** Pengguna menggunakan teks gaul (*slang*), singkatan, atau pengalihan karakter (seperti `s3lf h4rm`) untuk menghindari sensor otomatis.
* **Kebocoran Hak Akses:** Jika akun admin terkompromi, seluruh data mentah beserta identitas asli remaja berpotensi terekspos.

---

## 2. 📝 Fleksibilitas Konten (Dinamis vs Statis)

### **Strengths (Kekuatan)**
* **Performa Tinggi:** Landing page statis saat ini memuat sangat cepat karena tidak terbebani oleh pemrosesan query database eksternal.
* **Aman dari Manipulasi:** Halaman edukasi dan landing page aman dari serangan injeksi database (seperti SQLi atau Stored XSS) karena datanya *hardcoded*.

### **Weaknesses (Kelemahan)**
* **Ketergantungan Kode:** Perubahan teks edukasi, artikel pencegahan, atau informasi kontak konselor mendesak menuntut pengembang untuk memodifikasi file Blade dan melakukan deployment ulang.

### **Opportunities (Peluang)**
* **Manajemen Konten Hibrida:** Mengaktifkan model `ProgramContent` dan `KonselorContact` dengan antarmuka CRUD admin, namun menggunakan **caching** (seperti Laravel Cache dengan Redis/File) selama 24 jam untuk menjaga performa landing page.

### **Threats (Ancaman)**
* **Kerentanan XSS Baru:** Admin yang memiliki hak akses CRUD dinamis berisiko memasukkan skrip berbahaya (Stored XSS) jika filter sanitasi input pada editor konten tidak ketat.

---

## 3. 📊 Kegunaan Riset (Ekspor Data)

### **Strengths (Kekuatan)**
* **Kemudahan Analisis:** Ekspor format Excel memudahkan para peneliti mengolah data tren kesehatan mental remaja secara luring menggunakan SPSS atau Excel.

### **Weaknesses (Kelemahan)**
* **Server Crash:** Ekspor data dalam jumlah besar secara sinkronus tanpa antrean (*queue*) dapat menyebabkan batas memori server habis (*memory limit*).
* **Risiko De-anonimisasi:** Penggabungan variabel data seperti kota asal, tanggal kirim, dan gaya bahasa ekspresi dapat memicu pengenalan kembali identitas remaja secara tidak sengaja oleh peneliti.

### **Opportunities (Peluang)**
* **Ekspor Berbasis Antrean (Queued Export):** Memproses permintaan ekspor di latar belakang, lalu mengirimkan tautan unduhan aman setelah selesai.
* **Anonimisasi Agregat:** Mengaburkan data tanggal (hanya menyimpan bulan/tahun) dan melakukan *masking* otomatis pada kata-kata yang merujuk pada nama sekolah atau nama orang di dalam teks.

### **Threats (Ancaman)**
* **Pencurian Data Spreadsheet:** File Excel yang diunduh ke komputer peneliti rentan bocor secara fisik/digital jika perangkat peneliti terinfeksi malware.

---

## 🛠️ Rencana Aksi Optimasi (Action Plan)

1. **Fase 1: Keamanan & Sanitasi (High Priority)**
   * Implementasikan Laravel Spatie Activitylog untuk merekam tindakan moderasi dan ekspor data.
   * Pasang sanitasi input (seperti HTMLPurifier) pada form input publik dan editor admin.

2. **Fase 2: Fungsionalitas Dinamis (Medium Priority)**
   * Hubungkan database `konselor_contacts` ke landing page & halaman terima kasih secara dinamis.
   * Bangun modul admin CRUD untuk mengelola data kontak konselor.

3. **Fase 3: Optimasi Ekspor & AI Moderasi (Medium Priority)**
   * Ubah alur ekspor Excel menjadi *asynchronous job* dan gunakan *Signed URLs* untuk pengunduhan.
   * Tingkatkan akurasi `ExpressionModerationService` dengan regex variasi kata sensitif.
