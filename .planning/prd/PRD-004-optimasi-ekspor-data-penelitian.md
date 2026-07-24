# PRD-004: Optimasi Ekspor Data Penelitian (Queued Export + Data Masking + Secure Download)

| Field | Detail |
|-------|--------|
| **PRD ID** | PRD-004 |
| **Versi** | 1.0 |
| **Status** | Approved |
| **Penulis** | Kang Dadang (PRD Specialist) |
| **Tanggal** | 2026-07-23 |
| **Prioritas** | High |
| **Target Release** | Sprint 3 |
| **RICE Score** | 160 |
| **Approved By** | Mas Lutfi (Product Owner) & Sophia (PM) |
| **Approval Date** | 2026-07-24 |
| **Security Reviewer** | Kang Hendra |
| **Security Approved** | Approved |

---

## Kalkulasi RICE Score

| Parameter | Nilai | Keterangan |
|-----------|-------|------------|
| Reach | 10 user/bulan | Tim peneliti yang melakukan ekspor data |
| Impact | 3.0 — Massive | Ekspor yang crash atau bocor data identitas berdampak fatal bagi riset |
| Confidence | 80% | Sistem ekspor existing sudah ada; masalah teridentifikasi dengan jelas |
| Effort | 3 person-weeks | Estimasi implementasi queue + masking + secure URL |
| **RICE Score** | **(10 × 3.0 × 0.80) / 3 = 8** | *Angka RICE kecil karena Reach kecil, tapi impact-nya critical* |

> **Catatan Penting:** RICE Score angka kecil tidak mencerminkan kritisitas fitur ini. Ini adalah fitur **keamanan data** — kegagalan atau kebocoran data dapat:
> 1. Membatalkan legalitas riset akademik OPSI 2026
> 2. Melanggar kerahasiaan sumber (identitas remaja bocor)
> 3. Crash server saat dataset besar diekspor langsung
> 
> Oleh karena itu prioritas tetap **High** dan eksekusi di Sprint 3 setelah fondasi (PRD-001) dan fitur operasional (PRD-002, PRD-003) selesai.

---

## 1. Ringkasan

PRD ini mengoptimasi sistem ekspor data penelitian Aplikasi STEP dari pendekatan sinkronus (langsung download, rentan crash memory) menjadi asinkronus berbasis queue (background job), dilengkapi dengan data masking untuk melindungi identitas remaja, dan sistem secure download URL yang aman untuk peneliti.

Saat ini ekspor Excel berjalan sinkronus via Maatwebsite Excel — jika dataset besar, proses ini bisa crash karena PHP memory limit. Lebih kritis lagi: tidak ada mekanisme data masking, sehingga ada risiko peneliti menerima data yang bisa digunakan untuk de-anonimisasi identitas remaja (yang seharusnya dilindungi oleh enkripsi Laravel Crypt).

---

## 2. Latar Belakang & Masalah

- **Masalah saat ini:**
  - Ekspor Excel berjalan secara **sinkronus** — request HTTP tidak selesai sampai file Excel selesai dibuat
  - Untuk dataset besar (ribuan ekspresi), proses ini bisa memakan waktu > 30 detik dan berpotensi crash (PHP memory exhausted, timeout Nginx 30 detik)
  - **Tidak ada data masking**: kolom nama, meskipun dienkripsi di database, ketika diekspor bisa saja terdekripsi dan masuk ke file Excel
  - Tidak ada mekanisme "secure download" — file diunduh langsung tanpa validasi tambahan
  - Peneliti tidak mendapat notifikasi saat file siap
  - Tidak ada riwayat ekspor yang tercatat

- **Dampak jika tidak diselesaikan:**
  - Server crash saat dataset riset sudah besar (400+ ekspresi)
  - Potensi pelanggaran etika riset: identitas anonim remaja bisa terekspos
  - Tidak ada audit trail untuk ekspor data sensitif
  - Kepercayaan remaja terhadap anonimitas aplikasi hilang jika bocor

- **Solusi yang diusulkan:**
  1. **Queued Export:** Proses pembuatan file Excel dipindahkan ke background job (Laravel Queue)
  2. **Data Masking:** Semua identifier personal dianonimkan sebelum masuk file Excel
  3. **Secure Download:** File disimpan di storage private, diakses via URL dengan token sementara (signed URL, berlaku 1 jam)
  4. **Audit Trail:** Setiap ekspor dicatat (siapa, kapan, berapa baris, filter apa)

---

## 3. Tujuan & Metrik Keberhasilan

| Tujuan | Metrik | Target |
|--------|--------|--------|
| Eliminasi risiko crash ekspor | Jumlah ekspor yang gagal karena memory/timeout | 0 |
| Proteksi identitas dalam file ekspor | Kolom identitas personal dalam file Excel yang bisa digunakan untuk de-anonimisasi | 0 kolom |
| Keamanan akses file ekspor | File ekspor dapat diakses tanpa signed URL | Tidak pernah |
| Audit trail ekspor | Persentase ekspor yang tercatat di log | 100% |
| Waktu respons peneliti | Waktu dari klik "Ekspor" ke notifikasi "file siap" | < 60 detik untuk dataset ≤ 5.000 baris |

---

## 4. Scope

### In Scope
- Migrasi ekspor dari sinkronus ke **Laravel Queue** (job: `ExportExpressionsJob`)
- **Data masking rules** untuk file ekspor:
  - Nama: dihapus atau diganti dengan pseudonim (`Responden-{random_id}`)
  - IP address: tidak diikutsertakan
  - Nama asli yang terenkripsi: **tidak** didekripsi — kolom dikecualikan dari ekspor
  - Catatan moderasi internal admin: tidak diikutsertakan
- **Signed URL** untuk download file (berlaku 1 jam setelah link dibuat)
- File ekspor disimpan di `storage/private/exports/` (tidak aksesibel via URL publik)
- Riwayat ekspor: tabel `export_logs` mencatat siapa, kapan, filter apa, jumlah baris
- Notifikasi di halaman dashboard peneliti saat file siap (polling atau flash session)
- Filter ekspor yang sudah ada (per kategori) tetap tersedia
- Filter tambahan: rentang tanggal, status (approved/all)
- Cleanup otomatis: file ekspor dihapus setelah 24 jam

### Out of Scope
- Ekspor format lain selain Excel (CSV, PDF — enhancement masa depan)
- Notifikasi email ke peneliti saat file siap (enhancement masa depan)
- Enkripsi file Excel dengan password (enhancement masa depan)
- Real-time progress bar selama proses ekspor berlangsung (enhancement)
- Dashboard analitik dari data yang diekspor
- Integrasi dengan platform riset eksternal (SPSS, Google Sheets)

---

## 5. User Stories

| # | Sebagai | Saya ingin | Sehingga |
|---|---------|------------|----------|
| US-4.1 | Peneliti | Meminta ekspor data tanpa menunggu halaman browser loading lama | Saya bisa melanjutkan pekerjaan lain sementara sistem menyiapkan file ekspor |
| US-4.2 | Peneliti | Mendapat notifikasi saat file ekspor sudah siap diunduh | Saya tahu kapan tepatnya saya bisa mengunduh data |
| US-4.3 | Peneliti | Mengunduh file ekspor melalui link yang aman dan terbatas waktu | Hanya saya yang bisa mengakses file yang saya minta |
| US-4.4 | Peneliti | Memfilter data yang diekspor berdasarkan kategori dan rentang tanggal | Saya mendapatkan dataset yang relevan dengan kebutuhan analisis saya |
| US-4.5 | Peneliti | Melihat riwayat ekspor yang pernah saya lakukan | Saya bisa melacak kapan saya mengakses data dan data apa yang sudah saya unduh |
| US-4.6 | Tim Etika Riset / Admin | Memastikan file ekspor tidak mengandung data yang bisa mengidentifikasi responden | Anonimitas remaja terlindungi sesuai dengan prinsip etika riset |
| US-4.7 | Admin | Melihat log semua aktivitas ekspor data | Saya bisa mengaudit siapa yang mengakses data kapan dan untuk apa |
| US-4.8 | Sistem | Membersihkan file ekspor yang sudah lebih dari 24 jam secara otomatis | Storage tidak penuh dengan file ekspor yang sudah tidak dipakai |

---

## 6. Acceptance Criteria

| # | Given | When | Then |
|---|-------|------|------|
| AC-4.1 | Peneliti sudah login di `/researcher/dashboard` dan mengklik tombol "Ekspor Data" | Tombol diklik | Sistem **tidak** langsung download file; sebagai gantinya muncul notifikasi "Permintaan ekspor sedang diproses..." — job masuk ke queue |
| AC-4.2 | Job ekspor selesai diproses di background | Job berhasil selesai | Peneliti melihat tombol "Unduh File" atau notifikasi baru di dashboard: "File ekspor siap" |
| AC-4.3 | Peneliti mengklik "Unduh File" | Signed URL di-generate dan file didownload | File Excel berhasil diunduh; signed URL kedaluwarsa setelah 1 jam |
| AC-4.4 | Seseorang mencoba mengakses signed URL yang sudah kedaluwarsa | URL diakses | Sistem mengembalikan response 403 Forbidden atau 410 Gone |
| AC-4.5 | File Excel dari ekspor dibuka | Kolom dalam file dicek | File tidak mengandung: nama asli, email, IP address, catatan moderasi internal — hanya berisi: ID pseudonim, isi ekspresi, kategori, tanggal, status |
| AC-4.6 | Peneliti memfilter ekspor berdasarkan kategori "Keterlibatan Emosional" dan rentang 1 Januari – 30 Juni 2026 | Ekspor diproses | File Excel hanya berisi data dengan kategori tersebut dalam rentang tanggal yang dipilih |
| AC-4.7 | Admin membuka halaman log ekspor | Halaman dibuka | Log menampilkan: username peneliti, waktu request, filter yang digunakan, jumlah baris yang diekspor, waktu selesai |
| AC-4.8 | File ekspor sudah berumur lebih dari 24 jam | Cron job atau scheduler berjalan | File dihapus dari `storage/private/exports/` dan record di `export_logs` diperbarui status menjadi `expired` |
| AC-4.9 | Queue worker tidak berjalan saat peneliti meminta ekspor | Job timeout setelah 5 menit | Peneliti melihat notifikasi error dan bisa mencoba ulang — tidak ada crash yang mempengaruhi halaman lain |
| AC-4.10 | Dataset yang diekspor mengandung 500 baris ekspresi | Job diproses | File selesai dibuat dalam < 60 detik tanpa PHP fatal error atau memory exhausted |

---

## 7. Alur Utama (Happy Path)

### Alur Peneliti Ekspor Data
1. Peneliti login dan membuka `/researcher/dashboard`
2. Peneliti memilih filter: kategori (opsional), rentang tanggal (opsional), status (default: approved)
3. Peneliti mengklik tombol "Ekspor ke Excel"
4. Controller memvalidasi request dan membuat record `export_logs` dengan status `pending`
5. Controller mendispatch `ExportExpressionsJob` ke queue dengan parameter filter
6. Halaman menampilkan notifikasi: "Ekspor sedang diproses. Halaman ini akan memperbarui otomatis."
7. Background job berjalan: query data, terapkan data masking, buat file Excel
8. File disimpan di `storage/private/exports/{uuid}.xlsx`
9. Record `export_logs` diperbarui: status `completed`, path file, jumlah baris
10. Halaman peneliti memperbarui (polling 5 detik atau redirect) dan menampilkan tombol "Unduh File"
11. Peneliti mengklik "Unduh File" → sistem membuat signed URL sementara
12. Browser mengunduh file Excel via signed URL
13. Signed URL kedaluwarsa dalam 1 jam
14. Setelah 24 jam: file dihapus otomatis oleh scheduler

---

## 8. Business Rules

- **BR-4.1:** Satu peneliti hanya bisa memiliki 1 ekspor aktif (status `pending` atau `processing`) dalam satu waktu — tidak bisa queue ganda
- **BR-4.2:** File ekspor hanya bisa diunduh oleh peneliti yang sama yang memintanya — tidak bisa berbagi link ke orang lain (link dikunci per user via signed URL)
- **BR-4.3:** **Data masking wajib diterapkan** — field berikut TIDAK BOLEH ada dalam file ekspor dalam bentuk apapun yang bisa mengidentifikasi individu:
  - `nama` (meskipun sudah dienkripsi) → diganti `Responden-{pseudonim_hash}`
  - `ip_address` → dikecualikan
  - `catatan_moderasi` admin internal → dikecualikan
- **BR-4.4:** File ekspor **tidak pernah disimpan di storage publik** — selalu di `storage/private/`
- **BR-4.5:** Signed URL berlaku maksimal 1 jam sejak dihasilkan
- **BR-4.6:** File ekspor dihapus otomatis setelah 24 jam
- **BR-4.7:** Setiap permintaan ekspor WAJIB dicatat di `export_logs`
- **BR-4.8:** Ekspor hanya bisa dilakukan oleh user dengan role `researcher` atau `admin`
- **BR-4.9:** Hanya ekspresi dengan status `approved` yang diekspor secara default; filter status bisa diubah hanya oleh `admin`
- **BR-4.10:** Queue timeout untuk `ExportExpressionsJob` adalah 10 menit — jika melebihi, status diubah ke `failed` dan peneliti diberi notifikasi

---

## 9. Data Requirements

### Tabel Baru: `export_logs`

| Field | Tipe | Required | Validasi | Keterangan |
|-------|------|----------|----------|------------|
| `id` | bigint unsigned | Auto | — | PK auto-increment |
| `user_id` | bigint unsigned | Ya | FK users.id | Peneliti yang meminta ekspor |
| `status` | enum | Ya | pending, processing, completed, failed, expired | Status proses ekspor |
| `filter_params` | json | Tidak | — | Parameter filter yang digunakan (kategori, tanggal, status) |
| `file_path` | varchar(500) | Tidak | — | Path file di storage private (diisi saat completed) |
| `row_count` | int | Tidak | — | Jumlah baris yang diekspor |
| `requested_at` | timestamp | Ya | — | Waktu request |
| `completed_at` | timestamp | Tidak | — | Waktu selesai (null jika belum selesai) |
| `expires_at` | timestamp | Tidak | — | Waktu file kedaluwarsa (requested_at + 24 jam) |
| `error_message` | text | Tidak | — | Pesan error jika status = failed |
| `created_at` | timestamp | Auto | — | — |
| `updated_at` | timestamp | Auto | — | — |

### Kolom yang Diikutsertakan dalam File Ekspor

| Kolom di File Excel | Sumber dari DB | Transformasi |
|--------------------|----------------|--------------|
| ID Responden | `expressions.id` | Hash/pseudonim: `STEP-{hash8char}` |
| Isi Ekspresi | `expressions.content` | Apa adanya |
| Kategori | `categories.name` | Apa adanya |
| Tanggal Dikirim | `expressions.created_at` | Format: YYYY-MM-DD |
| Status | `expressions.status` | Apa adanya |
| Berisiko Tinggi | `expressions.is_risky` | Ya/Tidak |

### Kolom yang DIKECUALIKAN dari Ekspor

| Kolom | Alasan |
|-------|--------|
| `nama` (encrypted) | Identitas personal — tidak boleh dalam file ekspor |
| `ip_address` | Dapat digunakan untuk de-anonimisasi |
| `catatan_moderasi` | Data internal moderasi, bukan data riset |
| `flagged_reason` | Data internal moderasi |
| Email/kontak apapun | Tidak relevan dan sensitif |

---

## 10. Non-Functional Requirements

- **Performa:**
  - Job ekspor harus bisa menangani hingga 10.000 baris tanpa memory error (gunakan `chunk()` untuk query)
  - Maatwebsite Excel harus menggunakan mode `FromQuery` dengan chunk untuk dataset besar
  - Target: job selesai < 60 detik untuk 5.000 baris, < 3 menit untuk 10.000 baris

- **Keamanan:**
  - File tidak boleh di `public/` — hanya di `storage/private/`
  - Signed URL menggunakan Laravel's `Storage::temporaryUrl()` atau implementasi custom dengan HMAC signature
  - URL tidak bisa di-guess (UUID v4 untuk nama file)
  - Setiap download divalidasi: user yang mengunduh harus user yang sama yang meminta ekspor

- **Privasi Data (Critical):**
  - Data masking adalah **non-negotiable** — tidak ada pengecualian
  - Nama asli TIDAK PERNAH didekripsi untuk keperluan ekspor
  - IP address TIDAK PERNAH masuk ke file ekspor
  - File ekspor harus bisa diaudit (log mencatat apa yang diekspor)
  - Compliance dengan prinsip data minimization (hanya export data yang benar-benar diperlukan untuk riset)

- **Reliabilitas:**
  - Queue harus memiliki retry policy: maksimal 3 kali retry dengan exponential backoff
  - Jika semua retry gagal: status `failed`, notifikasi ke peneliti, alert ke admin
  - Queue driver yang direkomendasikan: database atau Redis (tidak sync)

- **Skalabilitas:**
  - Desain untuk bisa handle hingga 10 permintaan ekspor simultan dari peneliti berbeda

---

## 11. Dependencies

- **Hard dependency:**
  - **PRD-001** harus selesai (layout admin untuk dashboard peneliti)
- **Infrastruktur yang diperlukan:**
  - Laravel Queue (konfigurasi queue driver — minimal: database queue)
  - Laravel Scheduler (untuk cleanup file expired)
  - Maatwebsite Excel (sudah terpasang)
  - Storage private (`FILESYSTEM_DISK=local` sudah aktif)
- **Soft dependency:**
  - PRD-005 (Audit Log) dapat dikerjakan paralel — `export_logs` bisa dijadikan sebagian audit trail

---

## 12. Estimasi & Timeline

| Layer | Task Utama | Estimasi |
|-------|------------|----------|
| **Database** | Migration `create_export_logs_table` | 1 jam |
| **Backend** | `ExportExpressionsJob` (queue job + data masking + chunk query) | 5 jam |
| **Backend** | `ExportController` (request, dispatch job, status check, generate signed URL) | 3 jam |
| **Backend** | `ExportLogService` (create log, update status, cleanup) | 2 jam |
| **Backend** | Scheduler: cleanup file expired (24 jam) | 1 jam |
| **Backend** | Route + middleware untuk download secured | 1 jam |
| **Frontend** | Update halaman `/researcher/dashboard` (tombol ekspor, filter, status polling) | 3 jam |
| **Frontend** | Halaman log ekspor untuk admin dan peneliti | 2 jam |
| **Testing** | Uji queue (berhasil, gagal, timeout), data masking, signed URL expiry | 5 jam |
| **Total** | | **23 jam (~3 hari kerja)** |

> Assigned: Database → Kang Eka | Backend → Kang Bayu | Frontend → Teh Ayu | Testing → Kang Farhan | Security Review → Kang Hendra

---

## 13. Risks & Mitigasi

| Risk | Likelihood | Impact | Score | Level | Mitigasi |
|------|-----------|--------|-------|-------|----------|
| Queue worker tidak berjalan di production | 3 | 4 | 12 | Medium | Setup Supervisor untuk Laravel Queue worker; monitoring queue health |
| Data masking terlewat untuk field tertentu | 2 | 5 | 10 | Medium | Unit test khusus untuk memverifikasi setiap kolom dalam file ekspor |
| Storage private penuh karena file tidak terhapus | 2 | 3 | 6 | Medium | Scheduler cleanup + alert jika disk usage > 80% |
| Signed URL disalahgunakan (link disebarkan) | 2 | 3 | 6 | Medium | URL dikunci per user_id; jika akses dari user lain → 403 |
| Memory exhausted untuk dataset sangat besar (>10k rows) | 2 | 4 | 8 | Medium | Gunakan `chunk()` pada query, `ShouldQueue` dengan queue timeout 10 menit |
| File korup akibat job interrupted | 2 | 3 | 6 | Medium | Tulis ke file temp dulu, rename ke final hanya jika sukses penuh |
| Peneliti panic saat file tidak siap setelah 5 menit | 3 | 2 | 6 | Medium | Tampilkan estimasi waktu dan status progress yang informatif |

---

## 14. API Specification

> Tidak ada API publik untuk ekspor. Semua operasi melalui halaman web yang terautentikasi.

### Route Internal

| Method | Route | Middleware | Deskripsi |
|--------|-------|-----------|-----------|
| POST | `/researcher/export/request` | role_or_permission:researcher\|admin | Buat request ekspor baru |
| GET | `/researcher/export/status/{log_id}` | role_or_permission:researcher\|admin | Cek status ekspor |
| GET | `/researcher/export/download/{log_id}` | role_or_permission:researcher\|admin | Generate signed URL & redirect download |
| GET | `/admin/export-logs` | role:admin | Lihat semua log ekspor |

---

## 15. State Diagram

```
ExportLog States:
  [pending] → (job dispatched) → [processing] → (job done) → [completed]
                                              ↘ (job failed, retry habis) → [failed]
  [completed] → (24 jam lewat) → [expired] → (scheduler delete file)

File State:
  [tidak ada] → (job selesai) → [tersedia di storage/private/] → (expired) → [dihapus]
```

---

## 16. Database Schema Changes

### Tabel Baru: `export_logs`
(Lihat Data Requirements di atas)

> **Index yang direkomendasikan:**
> - `INDEX(user_id, status)` — untuk cek ekspor aktif per user
> - `INDEX(expires_at)` — untuk scheduler cleanup

---

## 17. Migration Plan

1. Buat migration: `create_export_logs_table`
2. Buat queue jobs table (jika belum ada): `php artisan queue:table && php artisan migrate`
3. Konfigurasi queue driver di `.env` (`QUEUE_CONNECTION=database`)
4. Setup Laravel Scheduler di server (cron: `* * * * * php artisan schedule:run`)
5. Test job di development dengan `php artisan queue:work`
6. Deploy ke production dengan Supervisor untuk persistent queue worker

---

## 18. Rollback Strategy

- **Trigger rollback:** Job ekspor menyebabkan error yang berdampak ke halaman lain
- **Langkah rollback:**
  1. Revert `ExportController` ke versi sinkronus lama (download langsung, sementara)
  2. Investigasi error di queue log
  3. Fix dan redeploy
- **Estimasi waktu rollback:** < 15 menit
- **Data recovery:** File ekspor yang tersimpan tidak perlu direcovery — tinggal request ulang

---

## 19. Monitoring & Alerting

- **Metrik yang dimonitor:**
  - Jumlah job di queue (antrian tidak boleh > 10 job selama > 5 menit)
  - Job failure rate (target: 0%)
  - Disk usage `storage/private/exports/`
- **Alert threshold:**
  - Job failure rate > 0 → alert ke admin
  - Queue stuck > 5 menit → alert ke DevOps
  - Disk usage > 80% → alert ke admin

---

## 20. Documentation Updates Required

- [ ] Panduan peneliti: cara mengekspor data dan mengunduh file
- [ ] Panduan admin: cara memantau log ekspor dan membersihkan manual jika perlu
- [ ] Dokumentasi teknis: setup queue worker dan scheduler di server production
- [ ] Data dictionary: kolom apa saja yang ada di file ekspor dan artinya

---

## Changelog

| Versi | Tanggal | Perubahan | Oleh |
|-------|---------|-----------|------|
| 1.0 | 2026-07-23 | Initial draft — Optimasi Ekspor Data Penelitian | Kang Dadang |

---

## Approval

| Role | Nama | Status | Tanggal |
|------|------|--------|---------|
| Product Owner | Mas Lutfi | Pending | — |
| PM Manager | Sophia | Pending | — |
| Tech Lead | — | Pending | — |
| Security Reviewer | Kang Hendra | Pending | — |

---

*PRD ini adalah dokumen hidup. Setiap perubahan setelah approval harus melalui proses versioning dan re-approval.*
