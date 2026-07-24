# [SECURITY AUDIT] - TEMUAN #01: Optimasi Ekspor Data Penelitian (Queued Export + Data Masking + Secure Download)
Tanggal Deteksi: 2026-07-24
Penemu: Kang Hendra (AppSec Specialist) via Kang Dika

## 🚨 Ringkasan Risiko
*   **Vulnerability Type**: Insecure Direct Object Reference (IDOR) & Sensitive Data Exposure (HIPAA / UU PDP Compliance)
*   **CVSS v3.1 Score**: 8.5 (High)
*   **Vector String**: CVSS:3.1/AV:N/AC:L/PR:L/UI:N/S:C/C:H/I:N/A:N
*   **Affected Component**: `app/Http/Controllers/ExportController.php` & `app/Exports/SecureExpressionsExport.php`

## 🔍 Detail Temuan (Proof of Concept)
1. **Exposure of Sensitive Research Data**: Data penelitian remaja rentan terekspos jika nama asli didekripsi dan IP Address, email, atau catatan moderasi internal disertakan dalam berkas ekspor Excel.
2. **Download URL Guessing (IDOR)**: URL unduhan file ekspor sebelumnya menggunakan ID berurutan atau path statis publik yang dapat ditebak, sehingga peneliti lain atau penyerang eksternal dapat mengunduh data tanpa memiliki otoritas atas log ekspor tersebut.
3. **Penyimpanan Publik**: File yang diletakkan di direktori publik server web (`public/storage`) dapat dibaca secara langsung tanpa autentikasi Laravel.

## 🛠️ Rekomendasi Perbaikan & Verifikasi (Remediation)
Semua aspek di atas telah diperbaiki dengan implementasi sebagai berikut:
1. **Queued Background Job**: Menggunakan antrean Laravel (`ExportExpressionsJob`) untuk menghindari time-out memori (memory leak) pada dataset besar.
2. **Strict Data Masking**:
   - Kolom nama asli tetap dalam keadaan terenkripsi di database dan **TIDAK** didekripsi di dalam berkas ekspor.
   - Menggunakan Hashing Pseudonim yang aman: `Responden-{hash8char}` menggunakan `substr(hash('sha256', $expression->id . 'step_salt_secure_pseudonym'), 0, 8)`.
   - Mengeliminasi IP address, email, dan catatan internal moderasi dari file hasil ekspor.
3. **Secure Download (Private Path)**:
   - File Excel disimpan di private local disk path: `storage/app/private/exports/{uuid}.xlsx`.
4. **Temporary Signed URL + Ownership Validation**:
   - Link download diamankan menggunakan *Temporary Signed Route* (berlaku maksimal 1 jam).
   - Ditambahkan validasi kepemilikan log ekspor: `$exportLog->user_id === auth()->id()`.
5. **Auto Clean-up Task (Scheduler)**:
   - Scheduler berjalan harian untuk menghapus file secara permanen dari disk dan mengubah status log menjadi `expired` setelah melewati batas waktu 24 jam.

## 🚦 Status Verifikasi Security
Semua test case di `tests/Feature/SecureExportTest.php` telah dijalankan dan **LULUS (PASS)** 100%. Fitur aman digunakan untuk data responden remaja.
