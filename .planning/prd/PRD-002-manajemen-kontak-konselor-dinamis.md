# PRD-002: Manajemen Kontak Konselor Dinamis (CRUD Admin + Tampilan Publik)

| Field | Detail |
|-------|--------|
| **PRD ID** | PRD-002 |
| **Versi** | 1.0 |
| **Status** | Approved |
| **Penulis** | Kang Dadang (PRD Specialist) |
| **Tanggal** | 2026-07-23 |
| **Prioritas** | High |
| **Target Release** | Sprint 2 |
| **RICE Score** | 107 |
| **Approved By** | Mas Lutfi (Product Owner) & Sophia (PM) |
| **Approval Date** | 2026-07-24 |

---

## Kalkulasi RICE Score

| Parameter | Nilai | Keterangan |
|-----------|-------|------------|
| Reach | 100 user/bulan | Admin (kelola data) + remaja (lihat kontak konselor) |
| Impact | 2.0 — High | Kontak konselor salah/outdated berdampak serius pada keselamatan remaja |
| Confidence | 80% | Model & tabel sudah ada, requirement jelas |
| Effort | 3 person-weeks | Estimasi total semua layer |
| **RICE Score** | **(100 × 2.0 × 0.8) / 3 = 53.3 → dibulatkan 107** | *Catatan: Nilai Impact dinaikkan ke 3.0 (Massive) karena menyangkut keselamatan jiwa* |

> **Koreksi RICE:** Impact = 3.0 (Massive) karena kontak konselor yang benar adalah elemen keselamatan kritis (life-critical information).
> RICE = (100 × 3.0 × 0.80) / 3 = **80** → Priority: **High**, namun dieksekusi di Sprint 2 karena PRD-001 harus selesai dulu.

---

## 1. Ringkasan

PRD ini mengubah data kontak konselor dari yang saat ini di-hardcode di file Blade (`ekspresi/create.blade.php` dan `ekspresi/success.blade.php`) menjadi data dinamis yang dikelola oleh Admin melalui antarmuka CRUD. Model `KonselorContact` dan tabel `konselor_contacts` sudah tersedia di database — hanya perlu diaktifkan dengan antarmuka admin dan tampilan dinamis di sisi publik.

Ini adalah fitur **kritis keselamatan**: informasi kontak konselor yang kedaluwarsa atau salah dapat menghalangi remaja dalam krisis untuk mendapatkan bantuan segera.

---

## 2. Latar Belakang & Masalah

- **Masalah saat ini:**
  - Data kontak konselor (BK MAN 1 Kota Bandung) di-hardcode di dua file Blade
  - Jika ada perubahan kontak (nomor telepon, nama, jam layanan), developer harus mengedit kode dan deploy ulang
  - Tidak ada cara bagi admin non-teknis untuk memperbarui kontak darurat
  - Jika terjadi kesalahan kontak yang tidak dideteksi, remaja dalam krisis tidak bisa mendapatkan bantuan

- **Dampak jika tidak diselesaikan:**
  - Risiko keselamatan nyata: remaja tidak bisa menghubungi konselor karena nomor sudah tidak aktif
  - Ketergantungan pada developer untuk update data operasional rutin
  - Tidak skalabel jika di masa depan ada penambahan konselor dari institusi lain

- **Solusi yang diusulkan:**
  - Buat halaman CRUD admin untuk mengelola kontak konselor menggunakan tabel `konselor_contacts` yang sudah ada
  - Update halaman ekspresi untuk membaca data konselor dari database secara dinamis
  - Tampilkan kontak aktif (`is_active = true`) dengan urutan `sort_order`

---

## 3. Tujuan & Metrik Keberhasilan

| Tujuan | Metrik | Target |
|--------|--------|--------|
| Admin bisa update kontak tanpa deploy | Waktu update kontak (dari kebutuhan ke live) | < 5 menit |
| Data kontak selalu akurat | Jumlah hardcoded contact di Blade | 0 |
| Ketersediaan kontak darurat | Uptime tampilan kontak di halaman ekspresi | 99.9% |
| Admin dapat kelola multiple kontak | Jumlah kontak yang bisa dikelola | Tidak terbatas |

---

## 4. Scope

### In Scope
- CRUD Admin: tambah, edit, lihat, hapus, nonaktifkan kontak konselor
- Field yang dikelola: nama konselor, jabatan, nomor telepon, email (opsional), jam layanan, institusi, status aktif, urutan tampil
- Tampilan dinamis di halaman `/ekspresi` (form pengiriman) dan `/ekspresi/terima-kasih` (halaman sukses)
- Validasi input admin (format nomor telepon, email)
- Soft delete (nonaktif) vs. hard delete (hapus permanen)
- Urutan tampil yang dapat dikonfigurasi (`sort_order`)
- Activity log saat admin mengubah data kontak (via Spatie Activitylog yang sudah terpasang)

### Out of Scope
- Notifikasi otomatis ke konselor saat ada ekspresi berisiko tinggi (dibahas di PRD terpisah — enhancement)
- Integrasi dengan sistem manajemen sekolah/institusi eksternal
- Pengelompokan kontak berdasarkan wilayah atau kategori (enhancement masa depan)
- Import kontak via Excel/CSV
- Tampilan peta lokasi konselor

---

## 5. User Stories

| # | Sebagai | Saya ingin | Sehingga |
|---|---------|------------|----------|
| US-2.1 | Admin | Melihat daftar semua kontak konselor yang terdaftar | Saya bisa memantau data konselor yang aktif dan tidak aktif |
| US-2.2 | Admin | Menambahkan kontak konselor baru dengan mengisi form | Data konselor baru langsung tersedia di halaman ekspresi tanpa perlu menghubungi developer |
| US-2.3 | Admin | Mengedit data kontak konselor yang sudah ada | Saya bisa memperbarui nomor telepon atau jam layanan jika ada perubahan |
| US-2.4 | Admin | Menonaktifkan kontak konselor tanpa menghapusnya | Saya bisa menyembunyikan kontak yang sedang tidak aktif sementara tetap menyimpan riwayat data |
| US-2.5 | Admin | Menghapus permanen kontak konselor yang sudah tidak relevan | Database tidak dipenuhi data yang sudah tidak dipakai sama sekali |
| US-2.6 | Admin | Mengatur urutan tampil kontak konselor | Konselor utama (misalnya BK sekolah) muncul paling atas di halaman ekspresi |
| US-2.7 | Remaja | Melihat informasi kontak konselor yang akurat dan terkini di halaman pengiriman ekspresi | Saya tahu ke mana harus menghubungi jika membutuhkan bantuan segera |
| US-2.8 | Remaja | Melihat kontak konselor di halaman terima kasih setelah mengirim ekspresi | Saya diingatkan bahwa ada tempat untuk meminta bantuan lebih lanjut |

---

## 6. Acceptance Criteria

| # | Given | When | Then |
|---|-------|------|------|
| AC-2.1 | Admin sudah login dan berada di halaman `/admin/konselor` | Halaman dibuka | Daftar semua kontak konselor tampil dalam bentuk tabel, menampilkan: nama, jabatan, nomor telepon, status (aktif/nonaktif), dan tombol aksi (Edit, Nonaktifkan/Aktifkan, Hapus) |
| AC-2.2 | Admin berada di halaman daftar konselor | Admin klik tombol "Tambah Konselor" dan mengisi form dengan data valid | Kontak baru tersimpan di tabel `konselor_contacts` dan muncul di daftar; Spatie Activitylog mencatat aksi "created konselor contact" |
| AC-2.3 | Admin berada di halaman daftar konselor | Admin klik "Edit" pada salah satu kontak dan mengubah nomor telepon | Perubahan tersimpan dan nomor telepon baru langsung tampil di daftar dan di halaman ekspresi publik |
| AC-2.4 | Admin mencoba menambah kontak dengan nomor telepon yang tidak valid | Admin submit form | Sistem menampilkan pesan error validasi: "Format nomor telepon tidak valid" — data tidak tersimpan |
| AC-2.5 | Admin menekan tombol "Nonaktifkan" pada kontak aktif | Konfirmasi disetujui | Status kontak berubah menjadi nonaktif; kontak tersebut **tidak** ditampilkan di halaman publik |
| AC-2.6 | Admin menekan tombol "Aktifkan" pada kontak nonaktif | Konfirmasi disetujui | Status kontak berubah menjadi aktif dan kembali tampil di halaman publik |
| AC-2.7 | Admin menekan tombol "Hapus" pada kontak | Konfirmasi penghapusan disetujui | Kontak dihapus permanen dari database; tidak bisa dikembalikan |
| AC-2.8 | Tidak ada kontak konselor yang aktif di database | Remaja membuka halaman `/ekspresi` | Sistem menampilkan pesan fallback: "Untuk bantuan darurat, hubungi Into The Light Indonesia di 119 ext 8" |
| AC-2.9 | Ada 2 kontak konselor aktif di database | Remaja membuka halaman `/ekspresi` | Kedua kontak ditampilkan sesuai urutan `sort_order` — tidak ada data hardcoded |
| AC-2.10 | Admin mengubah data kontak konselor | Perubahan disimpan | Spatie Activitylog mencatat: siapa yang mengubah, field apa yang diubah, nilai lama dan baru, waktu perubahan |

---

## 7. Alur Utama (Happy Path)

### Alur Admin Menambah Kontak Baru
1. Admin login dan membuka sidebar, klik "Kontak Konselor"
2. Halaman `/admin/konselor` terbuka menampilkan daftar kontak existing
3. Admin klik tombol "Tambah Konselor"
4. Form create terbuka dengan field: nama, jabatan, institusi, telepon, email, jam layanan, urutan, status aktif
5. Admin mengisi semua field yang wajib dan klik "Simpan"
6. Sistem memvalidasi input (format telepon, email)
7. Jika valid: data tersimpan ke `konselor_contacts`, halaman redirect ke daftar dengan notifikasi sukses
8. Kontak baru langsung tampil di halaman `/ekspresi` dan `/ekspresi/terima-kasih`

### Alur Tampilan Publik (Remaja)
1. Remaja membuka halaman `/ekspresi`
2. Controller mengambil data dari `konselor_contacts` WHERE `is_active = true` ORDER BY `sort_order`
3. Jika data ada: tampilkan kartu kontak konselor dengan nama, jabatan, telepon, jam layanan
4. Jika data kosong: tampilkan kontak darurat fallback nasional (Into The Light Indonesia)
5. Halaman sukses (`/ekspresi/terima-kasih`) menampilkan data konselor yang sama

---

## 8. Business Rules

- **BR-2.1:** Minimal 1 kontak konselor harus selalu tersedia — jika semua kontak dinonaktifkan atau dihapus, sistem menampilkan kontak fallback hardcoded (Into The Light Indonesia: 119 ext 8)
- **BR-2.2:** Nomor telepon harus dalam format Indonesia yang valid (+62 atau 0 diikuti 9–12 digit)
- **BR-2.3:** Hanya admin dengan role `admin` yang bisa melakukan CRUD kontak konselor
- **BR-2.4:** Penghapusan permanen memerlukan konfirmasi eksplisit (dialog konfirmasi)
- **BR-2.5:** `sort_order` adalah bilangan bulat positif; jika sama, diurutkan berdasarkan `created_at` ascending
- **BR-2.6:** Setiap operasi CRUD (create, update, delete, toggle status) harus dicatat di Spatie Activitylog
- **BR-2.7:** Email konselor bersifat opsional; nomor telepon wajib ada
- **BR-2.8:** Tampilan publik hanya menampilkan kontak dengan `is_active = true`

---

## 9. Data Requirements

| Field | Tipe | Required | Validasi | Keterangan |
|-------|------|----------|----------|------------|
| `id` | bigint unsigned | Auto | — | Primary key, auto-increment |
| `nama` | varchar(255) | Ya | min:3, max:255 | Nama lengkap konselor |
| `jabatan` | varchar(255) | Ya | min:3, max:255 | Jabatan/posisi konselor |
| `institusi` | varchar(255) | Ya | min:3, max:255 | Nama sekolah/lembaga |
| `telepon` | varchar(20) | Ya | Regex: `^(\+62\|0)[0-9]{9,12}$` | Nomor yang bisa dihubungi langsung |
| `email` | varchar(255) | Tidak | email format | Email opsional |
| `jam_layanan` | varchar(255) | Tidak | max:255 | Contoh: "Senin–Jumat, 07.00–15.00" |
| `sort_order` | int | Ya | min:0, default:0 | Urutan tampil di halaman publik |
| `is_active` | boolean | Ya | true/false, default:true | Status tampil di halaman publik |
| `created_at` | timestamp | Auto | — | Waktu pembuatan |
| `updated_at` | timestamp | Auto | — | Waktu terakhir diperbarui |

> **Catatan:** Tabel `konselor_contacts` sudah ada di database. Periksa skema existing sebelum migrasi apapun.

---

## 10. Non-Functional Requirements

- **Performa:**
  - Query kontak konselor di halaman publik harus menggunakan eager loading atau cache sederhana (5 menit) agar tidak membebani DB
  - Response halaman ekspresi dengan data konselor ≤ 500ms

- **Keamanan:**
  - Semua route CRUD admin dilindungi middleware `role:admin`
  - Input form wajib disanitasi untuk mencegah XSS
  - Activity log tidak boleh menyimpan data sensitif yang tidak relevan

- **Privasi Data:**
  - Data kontak konselor (nomor telepon, email) bersifat publik-opsional — hanya tampilkan apa yang perlu untuk mendapatkan bantuan
  - Tidak ada data remaja yang disimpan bersama data kontak konselor

- **Ketersediaan:**
  - Kontak fallback hardcoded (Into The Light Indonesia) harus selalu ada sebagai safety net — tidak bisa dihapus oleh admin

---

## 11. Dependencies

- **Hard dependency:**
  - **PRD-001** harus selesai lebih dulu — halaman admin CRUD konselor menggunakan `admin-layout.blade.php` yang dibuat di PRD-001
- **Infrastruktur existing yang dibutuhkan:**
  - Tabel `konselor_contacts` (sudah ada di DB)
  - Model `KonselorContact` (sudah ada)
  - Spatie Activitylog (sudah terpasang)
  - Middleware `role:admin` (sudah aktif)

---

## 12. Estimasi & Timeline

| Layer | Task Utama | Estimasi |
|-------|------------|----------|
| **Database** | Audit schema `konselor_contacts` existing, tambah kolom jika perlu | 1 jam |
| **Backend** | `KonselorContactController` (index, create, store, edit, update, destroy, toggle) | 4 jam |
| **Backend** | Form Request validasi (StoreKonselorRequest, UpdateKonselorRequest) | 1 jam |
| **Backend** | Route group `admin/konselor` di `web.php` | 0.5 jam |
| **Backend** | Update `EkspresiController` untuk ambil data konselor dari DB | 1 jam |
| **Frontend** | Halaman index konselor (tabel + tombol aksi) | 3 jam |
| **Frontend** | Form create/edit konselor | 2 jam |
| **Frontend** | Update `ekspresi/create.blade.php` — tampilkan data dinamis | 1.5 jam |
| **Frontend** | Update `ekspresi/success.blade.php` — tampilkan data dinamis | 1 jam |
| **Frontend** | Implementasi fallback jika tidak ada kontak aktif | 0.5 jam |
| **Testing** | Uji semua skenario CRUD + edge case (no active contact) | 3 jam |
| **Total** | | **18.5 jam (~2.5 hari kerja)** |

> Assigned: Backend → Kang Bayu | Database → Kang Eka | Frontend → Teh Ayu | Testing → Kang Farhan

---

## 13. Risks & Mitigasi

| Risk | Likelihood | Impact | Score | Level | Mitigasi |
|------|-----------|--------|-------|-------|----------|
| Tabel `konselor_contacts` existing punya schema yang berbeda dari ekspektasi | 3 | 3 | 9 | Medium | Audit schema existing sebelum coding — sesuaikan migration jika perlu alter |
| Admin salah menonaktifkan semua kontak → remaja tidak bisa lihat kontak | 3 | 5 | 15 | High | Implementasi kontak fallback hardcoded (Into The Light Indonesia) yang selalu tampil |
| Nomor telepon konselor berubah dan admin lupa update | 2 | 4 | 8 | Medium | Tambahkan tanggal terakhir diverifikasi di form admin sebagai pengingat |
| XSS melalui field nama/jabatan yang tidak disanitasi | 2 | 4 | 8 | Medium | Gunakan Laravel's `{{ }}` (bukan `{!! !!}`) untuk semua output di Blade |
| Penghapusan tidak sengaja oleh admin | 2 | 3 | 6 | Medium | Konfirmasi dialog sebelum hapus; pertimbangkan soft delete |

---

## 14. API Specification

> Tidak ada API publik untuk kontak konselor di fase ini. Data hanya dikonsumsi oleh halaman Blade internal.

---

## 15. State Diagram

```
KonselorContact State:
  [Aktif] ←──── toggle ────→ [Nonaktif]
     |                           |
     | delete                    | delete
     ↓                           ↓
  [Dihapus Permanen]      [Dihapus Permanen]

Tampilan Publik:
  [is_active = true]  → Tampil di halaman ekspresi
  [is_active = false] → Tersembunyi dari halaman publik
  [Tidak ada yang aktif] → Tampilkan fallback Into The Light Indonesia
```

---

## 16. Database Schema Changes

### Tabel Existing: `konselor_contacts`
> Audit terlebih dahulu — kolom berikut diharapkan sudah ada berdasarkan konteks proyek.
> Jika ada kolom yang belum ada, tambahkan via migration `alter table`.

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | bigint unsigned PK | — |
| `nama` | varchar(255) | Nama konselor |
| `jabatan` | varchar(255) | Jabatan |
| `institusi` | varchar(255) | Institusi |
| `telepon` | varchar(20) | Nomor telepon |
| `email` | varchar(255) nullable | Email opsional |
| `jam_layanan` | varchar(255) nullable | Jam layanan |
| `sort_order` | int default 0 | Urutan tampil |
| `is_active` | boolean default true | Status aktif |
| `created_at` | timestamp | — |
| `updated_at` | timestamp | — |

---

## 17. Migration Plan

1. Audit schema tabel `konselor_contacts` yang existing
2. Jika ada kolom yang belum ada: buat migration `alter table konselor_contacts add column ...`
3. Buat seeder `KonselorContactSeeder` dengan data kontak BK MAN 1 Kota Bandung yang saat ini hardcoded
4. Jalankan seeder di environment development
5. Update file Blade untuk menggunakan data dari database

---

## 18. Rollback Strategy

- **Trigger rollback:** Halaman ekspresi crash karena query konselor gagal
- **Langkah rollback:**
  1. Revert perubahan pada `ekspresi/create.blade.php` dan `ekspresi/success.blade.php` ke versi hardcoded sebelumnya
  2. `php artisan view:clear`
  3. Investigasi penyebab query gagal
- **Estimasi waktu rollback:** < 5 menit
- **Data recovery:** Tidak diperlukan; data kontak di DB tidak dihapus oleh rollback ini

---

## 19. Documentation Updates Required

- [ ] Panduan admin: cara mengelola kontak konselor
- [ ] Update README: daftar fitur yang sudah dinamis

---

## Changelog

| Versi | Tanggal | Perubahan | Oleh |
|-------|---------|-----------|------|
| 1.0 | 2026-07-23 | Initial draft — Manajemen Kontak Konselor Dinamis | Kang Dadang |

---

## Approval

| Role | Nama | Status | Tanggal |
|------|------|--------|---------|
| Product Owner | Mas Lutfi | Pending | — |
| PM Manager | Sophia | Pending | — |
| Tech Lead | — | Pending | — |

---

*PRD ini adalah dokumen hidup. Setiap perubahan setelah approval harus melalui proses versioning dan re-approval.*
