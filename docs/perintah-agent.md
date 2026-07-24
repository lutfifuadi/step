# Instruksi Antar Agen — Konversi dan Integrasi Font Landing Page STEP

## Tujuan
Menambahkan font baru yang lebih estetis ke halaman landing STEP, dengan konversi dari file TTF ke WOFF2 dan penggunaan font yang sama di halaman beranda, tentang, edukasi, dan pencegahan.

## Catatan Penting
- **Pemberitahuan agen**: Setiap progres pengerjaan harus menampilkan nama agen yang sedang menjawab.
- **Sapaan**: Setiap agen harus menyapa di awal jawaban dengan nama agen.
- **Koordinasi**: Koordinasi dilakukan seperti percakapan antarrekan kerja dalam bahasa Indonesia.
- **Validasi**: Pantau `storage/logs/laravel.log` setiap selesai perubahan.
- **Tahapan**: Jangan langsung ubah kode sampai instruksi antar agen tersimpan di file ini.

## Struktur Tugas Keagenan

### 1. Agen Gilang — Orchestrator
- **Tugas**: Mengatur alur kerja, memastikan setiap agen memahami tugasnya, dan memverifikasi hasil akhir.
- **Instruksi**: Pastikan Aulia menyiapkan asset font, Dika mengintegrasikan font ke CSS dan view, Intan memilih kombinasi font yang tepat, dan Sinta melakukan validasi.

### 2. Agen Aulia — Asset & Backend
- **Tugas**: Menyediakan file font WOFF2 dan memastikan file tersedia di aset.
- **Instruksi**: Temukan atau unduh font `Quicksand` atau `Poppins` dalam format TTF, konversi ke WOFF2, dan simpan file font di `resources/assets/fonts/` serta pastikan akses file melalui Vite atau asset pipeline.

### 3. Agen Dika — Frontend
- **Tugas**: Mengintegrasikan font baru ke halaman landing dan halaman statis.
- **Instruksi**: Gunakan `@font-face` di `resources/views/content/pages/pages-home.blade.php` atau CSS global yang dimuat semua halaman. Pastikan font diterapkan untuk heading dan teks body di halaman `home`, serta juga diterapkan di `about`, `education`, dan `prevention`.

### 4. Agen Intan — UX & Desain Font
- **Tugas**: Menentukan pilihan font yang sesuai untuk branding STEP.
- **Instruksi**: Pilih `Quicksand` atau `Poppins` dan tentukan kombinasi weight font untuk heading dan body. Sarankan font alternatif yang sesuai bila tidak tersedia.

### 5. Agen Sinta — QA & Validasi
- **Tugas**: Menguji tampilan dan memastikan tidak ada error.
- **Instruksi**: Cek halaman `home`, `tentang`, `edukasi`, dan `pencegahan`, pastikan font baru dimuat dan tampil konsisten. Verifikasi `laravel.log` setelah perubahan.

## Langkah Eksekusi
1. **Gilang** membuat instruksi ini.
2. **Aulia** menyiapkan font dan konversi WOFF2.
3. **Dika** mengintegrasikan font pada template.
4. **Intan** memberikan validasi estetika font.
5. **Sinta** memverifikasi tampilan dan log.
