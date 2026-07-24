# PRD-005: Peningkatan Sistem Moderasi & Keamanan (Audit Log, Sidebar Admin, Navbar Fix, Pembersihan Controller Redundan)

| Field | Detail |
|-------|--------|
| **PRD ID** | PRD-005 |
| **Versi** | 1.0 |
| **Status** | Approved |
| **Penulis** | Kang Dadang (PRD Specialist) |
| **Tanggal** | 2026-07-23 |
| **Prioritas** | High |
| **Target Release** | Sprint 1–2 (paralel dengan PRD-001) |
| **RICE Score** | 160 |
| **Approved By** | Mas Lutfi (Product Owner) & Sophia (PM) |
| **Approval Date** | 2026-07-24 |

---

## Kalkulasi RICE Score

| Parameter | Nilai | Keterangan |
|-----------|-------|------------|
| Reach | 20 user/bulan | Admin & peneliti yang menggunakan dashboard |
| Impact | 2.0 — High | Tanpa audit log & sidebar, moderasi tidak bisa dipertanggungjawabkan |
| Confidence | 80% | Spatie Activitylog sudah terpasang; masalah teridentifikasi |
| Effort | 2.5 person-weeks | Lebih ringan dari PRD-004 — sebagian infrastruktur sudah ada |
| **RICE Score** | **(20 × 2.0 × 0.80) / 2.5 = 12.8** | *Angka RICE kecil karena Reach kecil, tapi ini sistem backbone moderasi riset* |

> **Catatan:** Seperti PRD-004, angka RICE kecil karena target user-nya spesifik (hanya admin). Namun ini adalah **backbone operasional** riset — tanpa moderasi yang bisa diaudit, integritas data riset dipertanyakan. Prioritas tetap **High**.

---

## 1. Ringkasan

PRD ini menggabungkan empat perbaikan yang saling berkaitan pada sistem moderasi dan keamanan Aplikasi STEP:

1. **Audit Log Dashboard** — Membuat halaman yang menampilkan log aktivitas dari Spatie Activitylog yang sudah terpasang, sehingga setiap tindakan moderasi (approve, flag, hapus ekspresi) dapat dilihat, dilacak, dan dipertanggungjawabkan
2. **Peningkatan Alur Moderasi** — Menambahkan fitur yang meningkatkan efisiensi moderasi: bulk action (approve/flag banyak sekaligus), filter yang lebih kaya, dan tampilan detail yang lebih informatif
3. **Pembersihan Controller Redundan** — Menghapus `PublicPageController` dan `HomePage` yang tidak terpakai untuk menjaga kebersihan codebase
4. **Navbar Fix & Role-Aware Navigation** — Memastikan navigasi di seluruh aplikasi mendeteksi role dan status login dengan benar (sebagian tumpang tindih dengan PRD-001 — lihat Dependencies)

> **Catatan Scope:** Item #4 (Navbar Fix) sudah tercakup dalam PRD-001. PRD ini fokus pada item #1, #2, dan #3. Referensi ke Navbar Fix di sini sebagai reminder bahwa PRD-001 dan PRD-005 harus dikerjakan koordinatif.

---

## 2. Latar Belakang & Masalah

- **Masalah saat ini:**
  - Spatie Activitylog sudah terpasang dan tabel `activity_log` sudah ada, namun **tidak ada halaman yang menampilkan log tersebut** kepada admin
  - Admin tidak bisa melihat riwayat siapa yang mengapprove atau memflag ekspresi tertentu
  - Proses moderasi hanya bisa dilakukan satu per satu (tidak ada bulk action)
  - Ada 2 controller tidak terpakai: `PublicPageController` dan `HomePage` — menambah kebingungan developer baru
  - Tidak ada mekanisme notifikasi internal saat ada ekspresi berisiko tinggi masuk

- **Dampak jika tidak diselesaikan:**
  - Integritas moderasi tidak bisa diverifikasi — tidak ada bukti siapa yang melakukan apa
  - Admin harus approve ratusan ekspresi satu per satu saat dataset sudah besar
  - Codebase berantakan dengan controller tidak terpakai → confusing bagi developer
  - Ekspresi berisiko tinggi (`is_risky = true`) mungkin tidak tertangani dengan cepat

- **Solusi yang diusulkan:**
  - Buat halaman `/admin/audit-log` yang menampilkan aktivitas dari Spatie Activitylog
  - Implementasi bulk action di halaman daftar ekspresi
  - Hapus controller tidak terpakai
  - Tambahkan badge/indikator untuk ekspresi berisiko tinggi

---

## 3. Tujuan & Metrik Keberhasilan

| Tujuan | Metrik | Target |
|--------|--------|--------|
| Admin bisa melihat riwayat moderasi | Jumlah aksi moderasi yang tercatat dan bisa dilihat | 100% dari semua aksi moderasi |
| Efisiensi moderasi meningkat | Waktu rata-rata untuk moderate 50 ekspresi | Turun 60% dengan bulk action |
| Codebase bersih | Jumlah controller tidak terpakai | 0 |
| Ekspresi berisiko tertangani cepat | Waktu rata-rata dari ekspresi `flagged is_risky` masuk ke penanganan admin | < 1 jam (dengan notifikasi dashboard) |

---

## 4. Scope

### In Scope
- **Audit Log Dashboard:**
  - Halaman `/admin/audit-log` menampilkan log dari tabel `activity_log` (Spatie)
  - Filter: berdasarkan user, jenis aksi, tanggal, subject type
  - Paginasi (20 item per halaman)
  - Detail log: user, aksi, subject, data lama vs. baru (jika tersedia), timestamp

- **Peningkatan Moderasi:**
  - Bulk action di halaman daftar ekspresi: pilih multiple → approve semua / flag semua / hapus semua
  - Filter tambahan: `is_risky = true` sebagai filter khusus untuk ekspresi berisiko
  - Badge/label merah untuk ekspresi dengan `is_risky = true` di daftar
  - Kolom "Ditinjau oleh" di daftar ekspresi (dari activity log)
  - Sorting: urutkan berdasarkan created_at, status, is_risky

- **Pembersihan Codebase:**
  - Hapus `PublicPageController.php` (jika tidak dipakai oleh route manapun)
  - Hapus `HomePage.php` (atau file controller yang dimaksud)
  - Update `web.php` agar tidak ada route yang merujuk ke controller yang dihapus
  - Uji semua route yang ada setelah pembersihan

- **Catatan Moderasi yang Lebih Kaya:**
  - Saat admin memflag ekspresi, field "catatan moderasi" wajib diisi (saat ini opsional)
  - Tampilkan siapa yang memflag dan catatannya di halaman detail ekspresi

### Out of Scope
- AI/ML untuk moderasi otomatis (enhancement masa depan)
- Notifikasi email ke konselor saat ekspresi `is_risky = true` (enhancement masa depan)
- Real-time notifikasi (WebSocket) untuk ekspresi berisiko (enhancement masa depan)
- Escalation workflow untuk ekspresi berisiko
- Moderasi oleh multiple level reviewer
- Integrasi Spatie Activitylog dengan platform monitoring eksternal (Sentry, Datadog)

---

## 5. User Stories

| # | Sebagai | Saya ingin | Sehingga |
|---|---------|------------|----------|
| US-5.1 | Admin | Melihat halaman audit log yang menampilkan semua aktivitas moderasi | Saya bisa membuktikan kepada peneliti atau pihak etika bahwa moderasi dilakukan dengan benar |
| US-5.2 | Admin | Memfilter audit log berdasarkan tanggal, jenis aksi, atau nama admin | Saya bisa menemukan log spesifik dengan cepat |
| US-5.3 | Admin | Memilih beberapa ekspresi sekaligus dan mengapprove semua dengan satu klik | Saya tidak perlu membuka setiap ekspresi satu per satu saat harus memproses banyak ekspresi |
| US-5.4 | Admin | Melihat badge merah pada ekspresi yang ditandai berisiko tinggi di daftar | Saya bisa dengan cepat memprioritaskan penanganan ekspresi yang butuh perhatian segera |
| US-5.5 | Admin | Diwajibkan mengisi catatan saat memflag ekspresi | Ada dokumentasi alasan setiap keputusan moderasi — tidak bisa memflag tanpa alasan |
| US-5.6 | Admin | Melihat informasi "Ditinjau oleh [Nama Admin] pada [Waktu]" di detail ekspresi | Saya dan peneliti tahu siapa yang bertanggung jawab atas keputusan moderasi tersebut |
| US-5.7 | Developer | Tidak melihat controller yang tidak terpakai di direktori app/Http/Controllers | Codebase lebih bersih dan saya tidak bingung mana controller yang aktif |
| US-5.8 | Peneliti | Melihat data "Ditinjau oleh" di dashboard peneliti | Saya bisa menilai kualitas moderasi yang dilakukan |

---

## 6. Acceptance Criteria

| # | Given | When | Then |
|---|-------|------|------|
| AC-5.1 | Admin sudah login dan membuka `/admin/audit-log` | Halaman dibuka | Tabel log tampil dengan kolom: Waktu, Admin, Aksi, Subject, Detail — diurutkan dari terbaru; paginasi 20 per halaman |
| AC-5.2 | Admin menggunakan filter "Tanggal: 1 Juli 2026" di audit log | Filter diterapkan | Hanya log dari tanggal tersebut yang ditampilkan |
| AC-5.3 | Admin menggunakan filter "Aksi: approved" di audit log | Filter diterapkan | Hanya log aksi approve yang ditampilkan |
| AC-5.4 | Admin berada di halaman daftar ekspresi | Halaman dibuka | Ada checkbox di setiap baris dan toolbar bulk action di atas tabel (tersembunyi sampai ada item yang dipilih) |
| AC-5.5 | Admin memilih 5 ekspresi dengan status `pending` dan klik "Approve Semua" | Konfirmasi disetujui | Kelima ekspresi statusnya berubah ke `approved`; 5 entri baru muncul di audit log untuk setiap approve |
| AC-5.6 | Ada ekspresi dengan `is_risky = true` di daftar | Halaman daftar dibuka | Baris ekspresi tersebut memiliki badge/label merah "BERISIKO" yang terlihat jelas |
| AC-5.7 | Admin mengklik "Flag" pada ekspresi tanpa mengisi catatan moderasi | Form diflag tanpa catatan | Sistem menampilkan validasi error: "Catatan moderasi wajib diisi saat memflag ekspresi" — flag tidak tersimpan |
| AC-5.8 | Admin mengklik "Flag" pada ekspresi dengan catatan yang terisi | Flag disimpan | Status berubah ke `flagged`; catatan tersimpan; audit log mencatat aksi ini dengan catatan sebagai detail |
| AC-5.9 | Developer memeriksa `app/Http/Controllers/` | Direktori dicek | File `PublicPageController.php` dan `HomePage.php` tidak ada; semua route di `web.php` mengarah ke controller yang valid |
| AC-5.10 | Admin membuka halaman detail ekspresi yang sudah diapprove | Detail dibuka | Tertera: "Diapprove oleh [Nama Admin] pada [Tanggal & Waktu]" |
| AC-5.11 | Semua route di `web.php` diperiksa setelah pembersihan controller | `php artisan route:list` dijalankan | Tidak ada route yang mengarah ke controller yang tidak ada (tidak ada error 500 pada route manapun) |

---

## 7. Alur Utama (Happy Path)

### Alur Admin Melihat Audit Log
1. Admin login, buka sidebar, klik "Audit Log"
2. Halaman `/admin/audit-log` terbuka menampilkan tabel log terbaru
3. Admin menggunakan filter: pilih tanggal range "1–23 Juli 2026", aksi "semua"
4. Tabel memperbarui menampilkan log sesuai filter
5. Admin mengklik detail salah satu log untuk melihat perubahan data (sebelum vs. sesudah)

### Alur Bulk Approve
1. Admin membuka `/admin/expressions` dengan filter status `pending`
2. Admin melihat 20 ekspresi pending — semuanya sudah dibaca dan dinilai aman
3. Admin mencentang semua checkbox (atau klik "Pilih Semua di Halaman Ini")
4. Toolbar bulk action muncul di atas tabel: "Approve (20)", "Flag (20)", "Hapus (20)"
5. Admin klik "Approve (20)"
6. Dialog konfirmasi muncul: "Yakin approve 20 ekspresi ini?"
7. Admin klik "Ya, Approve"
8. Sistem memproses approve untuk semua 20 ekspresi
9. Halaman reload dengan notifikasi: "20 ekspresi berhasil diapprove"
10. Audit log mencatat 20 entri baru

### Alur Pembersihan Controller
1. Developer mengidentifikasi `PublicPageController` dan `HomePage` tidak direferensikan di `web.php`
2. Developer memeriksa `php artisan route:list` untuk memastikan tidak ada route yang menggunakan controller ini
3. Developer menghapus kedua file controller
4. Developer menjalankan `php artisan route:list` lagi — tidak ada error
5. Developer menjalankan semua route test — lulus semua

---

## 8. Business Rules

- **BR-5.1:** Audit log adalah **read-only** — admin tidak bisa menghapus atau mengedit entri log
- **BR-5.2:** Catatan moderasi wajib diisi (minimal 10 karakter) saat melakukan aksi `flag` pada ekspresi
- **BR-5.3:** Bulk action maximum adalah 50 ekspresi per operasi untuk menghindari timeout
- **BR-5.4:** Bulk delete memerlukan konfirmasi yang lebih kuat: admin harus mengetikkan kata "HAPUS" untuk konfirmasi (karena tidak bisa di-undo)
- **BR-5.5:** Log dari Spatie Activitylog tidak boleh dihapus manual oleh admin — hanya bisa diarsipkan setelah 1 tahun (kebijakan retensi)
| **BR-5.6:** Setiap aksi moderasi (approve, flag, delete — baik individual maupun bulk) WAJIB tercatat di Spatie Activitylog
- **BR-5.7:** Controller yang dihapus harus dipastikan tidak ada referensi di route manapun sebelum dihapus
- **BR-5.8:** Hanya admin dengan role `admin` yang bisa melihat audit log
- **BR-5.9:** Filter audit log berdasarkan user hanya bisa dilakukan oleh admin super (jika ada hierarki admin) — untuk fase ini: semua admin bisa melihat semua log

---

## 9. Data Requirements

### Tabel Existing: `activity_log` (Spatie Activitylog)

| Field | Tipe | Keterangan |
|-------|------|------------|
| `id` | bigint | PK |
| `log_name` | varchar | Nama log grup |
| `description` | text | Deskripsi aksi |
| `subject_type` | varchar | Model yang dikenai aksi (App\Models\Expression) |
| `subject_id` | bigint | ID record yang dikenai aksi |
| `causer_type` | varchar | Model yang melakukan aksi (App\Models\User) |
| `causer_id` | bigint | ID user yang melakukan aksi |
| `properties` | json | Data sebelum dan sesudah perubahan |
| `created_at` | timestamp | Waktu aksi |

### Kolom yang Perlu Ditambah di `expressions` (jika belum ada)

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `moderated_by` | bigint unsigned nullable FK | User ID yang melakukan moderasi terakhir |
| `moderated_at` | timestamp nullable | Waktu moderasi terakhir |
| `catatan_moderasi` | text nullable | Catatan moderasi (wajib diisi saat flag) |

> Periksa apakah kolom `catatan_moderasi` dan `moderated_by` sudah ada di tabel `expressions` existing. Jika sudah ada dengan nama berbeda, sesuaikan.

---

## 10. Non-Functional Requirements

- **Performa:**
  - Query audit log harus menggunakan index pada `created_at` dan `causer_id`
  - Paginasi 20 item per halaman untuk mencegah load berlebihan
  - Bulk action untuk 50 item harus selesai < 5 detik

- **Keamanan:**
  - Halaman audit log hanya bisa diakses role `admin`
  - Log tidak bisa dihapus atau dimanipulasi via UI
  - Bulk action dilindungi dari CSRF (Laravel CSRF token)
  - Konfirmasi bulk delete memerlukan input teks "HAPUS"

- **Privasi Data:**
  - Audit log menampilkan nama admin (bukan data sensitif remaja)
  - Data lama vs. baru di properties log tidak boleh menampilkan nama terdekripsi remaja
  - Hanya nama display atau pseudonim yang boleh muncul di log

- **Integritas Data:**
  - Entri audit log tidak bisa diubah atau dihapus via aplikasi
  - Audit log harus mencatat semua aksi moderasi tanpa terkecuali

---

## 11. Dependencies

- **Hard dependency:**
  - **PRD-001** harus selesai — sidebar admin yang dibuat di PRD-001 adalah tempat link ke halaman Audit Log
- **Infrastruktur existing:**
  - Spatie Activitylog (sudah terpasang — tabel `activity_log` sudah ada)
  - Middleware `role:admin` (sudah aktif)
  - Tabel `expressions` (sudah ada)
- **Soft dependency:**
  - PRD-002 dan PRD-003 dapat dikerjakan paralel — audit log mereka otomatis tercatat via Spatie yang sudah dikonfigurasi

---

## 12. Estimasi & Timeline

| Layer | Task Utama | Estimasi |
|-------|------------|----------|
| **Database** | Migration: tambah kolom `moderated_by`, `moderated_at` ke `expressions` (jika belum ada) | 1 jam |
| **Database** | Tambah index `created_at`, `causer_id` di `activity_log` | 0.5 jam |
| **Backend** | `AuditLogController` (index + filter) | 2 jam |
| **Backend** | Update `ExpressionController` — tambah validasi catatan saat flag, logging Spatie | 2 jam |
| **Backend** | Implementasi bulk action di `ExpressionController` (bulk approve, flag, delete) | 3 jam |
| **Backend** | Hapus `PublicPageController` dan `HomePage`, update `web.php` | 1 jam |
| **Frontend** | Halaman `/admin/audit-log` dengan tabel + filter | 3 jam |
| **Frontend** | Update halaman daftar ekspresi: checkbox bulk, toolbar bulk action, badge berisiko | 3 jam |
| **Frontend** | Update detail ekspresi: tampilkan "Ditinjau oleh" | 1 jam |
| **Frontend** | Modal konfirmasi bulk delete dengan input "HAPUS" | 1 jam |
| **Testing** | Uji audit log (filter, paginasi), bulk action (sukses, parsial gagal), validasi catatan flag | 4 jam |
| **Total** | | **21.5 jam (~2.5 hari kerja)** |

> Assigned: Database → Kang Eka | Backend → Kang Bayu | Frontend → Teh Ayu | Testing → Kang Farhan

---

## 13. Risks & Mitigasi

| Risk | Likelihood | Impact | Score | Level | Mitigasi |
|------|-----------|--------|-------|-------|----------|
| Bulk delete tidak sengaja menghapus data penting | 3 | 5 | 15 | High | Konfirmasi ganda dengan input teks "HAPUS"; soft delete dengan grace period 7 hari |
| Query audit log lambat untuk dataset besar | 3 | 3 | 9 | Medium | Index yang tepat pada `created_at` dan `causer_id`; paginasi wajib |
| Controller yang dihapus ternyata masih dipakai di route tersembunyi | 2 | 4 | 8 | Medium | Jalankan `php artisan route:list` sebelum dan sesudah penghapusan; review kode secara manual |
| Spatie Activitylog properties mengandung data sensitif yang tidak perlu | 2 | 3 | 6 | Medium | Konfigurasi `logOnlyDirty()` dan tentukan kolom yang boleh dilog di model Expression |
| Bulk action timeout untuk 50 item dengan relasi kompleks | 2 | 2 | 4 | Low | Test performa bulk action; gunakan transaction untuk atomicity |

---

## 14. Wireframe / Mockup Reference

### Halaman Audit Log
```
+-----------------------------------------------------------+
| AUDIT LOG                                                 |
| Filter: [Semua Aksi ▼] [Semua Admin ▼] [Tgl Mulai] [Tgl Akhir] [Cari] |
+-----------------------------------------------------------+
| Waktu           | Admin    | Aksi     | Subject       | Detail |
| 23 Jul 2026 10:35 | Admin1 | approved | Ekspresi #124 | [Lihat] |
| 23 Jul 2026 10:34 | Admin1 | flagged  | Ekspresi #123 | [Lihat] |
| 23 Jul 2026 09:10 | Admin2 | created  | Konselor BK   | [Lihat] |
+-----------------------------------------------------------+
| [Prev] 1 2 3 ... 10 [Next]                                |
```

### Halaman Daftar Ekspresi dengan Bulk Action
```
+-----------------------------------------------------------+
| DAFTAR EKSPRESI           [Status: Pending ▼] [Berisiko ▼] |
+-----------------------------------------------------------+
| [✅ Pilih Semua] | BULK: [Approve (3)] [Flag (3)] [Hapus (3)] |
+-----------------------------------------------------------+
| [☐] | #123 | "Ayahku tidak pernah..."  | 20 Jul | [BERISIKO 🔴] | Pending | [Detail] |
| [☑] | #124 | "Kadang aku rindu..."     | 21 Jul |               | Pending | [Detail] |
| [☑] | #125 | "Waktu kecil, bapak..."   | 22 Jul |               | Pending | [Detail] |
| [☑] | #126 | "Aku tidak kenal..."      | 23 Jul |               | Pending | [Detail] |
+-----------------------------------------------------------+
```

---

## 15. State Diagram

```
Expression Moderation State Machine:
  [pending] ──→ approve ──→ [approved]
     |                          |
     +──→ flag (+ catatan) ──→ [flagged]
     |
     +──→ delete ──→ [deleted] (soft delete, 7 hari grace)

Bulk Action:
  [Multiple selected] → bulk_approve → semua menjadi [approved]
                     → bulk_flag    → semua menjadi [flagged] (catatan bulk)
                     → bulk_delete  → konfirmasi input "HAPUS" → semua [deleted]

Audit Log State:
  [Setiap aksi moderasi] → activity_log entry created → [immutable]
```

---

## 16. Database Schema Changes

### Tambahan Kolom di `expressions` (jika belum ada)

| Kolom Baru | Tipe | Default | Keterangan |
|-----------|------|---------|------------|
| `moderated_by` | bigint unsigned nullable | NULL | FK ke `users.id` — siapa yang moderasi terakhir |
| `moderated_at` | timestamp nullable | NULL | Kapan moderasi terakhir |
| `catatan_moderasi` | text nullable | NULL | Catatan saat memflag (wajib saat flag) |

> **Index:** Tambahkan `INDEX(created_at, causer_id)` di tabel `activity_log` jika belum ada.

---

## 17. Migration Plan

1. Audit tabel `expressions` — cek apakah `moderated_by`, `moderated_at`, `catatan_moderasi` sudah ada
2. Jika belum: buat migration `add_moderation_columns_to_expressions_table`
3. Audit tabel `activity_log` — cek index yang ada
4. Tambahkan index jika belum ada: `INDEX(created_at)`, `INDEX(causer_id, created_at)`
5. Hapus controller redundan setelah verifikasi route
6. Deploy dalam urutan: migration → controller cleanup → feature update

---

## 18. Rollback Strategy

- **Trigger rollback:** Bulk action menyebabkan data corrupt atau audit log error
- **Langkah rollback:**
  1. Revert `ExpressionController` ke versi sebelum bulk action
  2. Rollback migration tambahan kolom (jika diperlukan)
  3. Restore controller yang dihapus dari Git
- **Estimasi waktu rollback:** < 15 menit
- **Data recovery:** Soft delete memberikan grace period 7 hari untuk recovery ekspresi yang terhapus

---

## 19. Monitoring & Alerting

- **Metrik yang dimonitor:**
  - Jumlah ekspresi `is_risky = true` yang belum ditangani (target: < 1 jam tanpa penanganan)
  - Rata-rata waktu moderasi per ekspresi
- **Alert threshold:**
  - Jika ada ekspresi `is_risky = true` yang sudah > 1 jam belum ditindaklanjuti → alert dashboard untuk admin
- **Dashboard:** Badge notifikasi di sidebar admin untuk ekspresi berisiko yang pending

---

## 20. Documentation Updates Required

- [ ] Panduan admin: cara menggunakan bulk action dengan aman
- [ ] Panduan admin: cara membaca audit log
- [ ] Update README: list controller yang aktif (setelah pembersihan)
- [ ] Kebijakan retensi audit log (berapa lama log disimpan)

---

## Changelog

| Versi | Tanggal | Perubahan | Oleh |
|-------|---------|-----------|------|
| 1.0 | 2026-07-23 | Initial draft — Sistem Moderasi & Keamanan | Kang Dadang |

---

## Approval

| Role | Nama | Status | Tanggal |
|------|------|--------|---------|
| Product Owner | Mas Lutfi | Pending | — |
| PM Manager | Sophia | Pending | — |
| Tech Lead | — | Pending | — |

---

*PRD ini adalah dokumen hidup. Setiap perubahan setelah approval harus melalui proses versioning dan re-approval.*
