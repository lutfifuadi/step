# PRD-003: Manajemen Konten Landing Page Dinamis (Program Content CMS)

| Field | Detail |
|-------|--------|
| **PRD ID** | PRD-003 |
| **Versi** | 1.0 |
| **Status** | Approved |
| **Penulis** | Kang Dadang (PRD Specialist) |
| **Tanggal** | 2026-07-23 |
| **Prioritas** | High |
| **Target Release** | Sprint 2–3 |
| **RICE Score** | 80 |
| **Approved By** | Mas Lutfi (Product Owner) & Sophia (PM) |
| **Approval Date** | 2026-07-24 |

---

## Kalkulasi RICE Score

| Parameter | Nilai | Keterangan |
|-----------|-------|------------|
| Reach | 150 user/bulan | Admin (kelola) + semua pengunjung (baca landing page) |
| Impact | 2.0 — High | Konten statis membutuhkan deploy ulang setiap ada perubahan copy |
| Confidence | 80% | Model & tabel `program_contents` sudah ada |
| Effort | 3.75 person-weeks | Estimasi total semua layer (lebih kompleks dari PRD-002) |
| **RICE Score** | **(150 × 2.0 × 0.80) / 3.75 = 64** | *Priority: Medium-High* |

> Dinaikkan ke **High** karena tanpa CMS ini, setiap pembaruan konten riset memerlukan developer dan deploy — tidak praktis untuk tim OPSI.

---

## 1. Ringkasan

PRD ini membangun sistem CMS (Content Management System) sederhana berbasis tabel `program_contents` yang sudah tersedia di database, untuk mengelola konten halaman-halaman landing page Aplikasi STEP secara dinamis. Saat ini seluruh konten di halaman `/`, `/tentang`, `/edukasi`, dan `/pencegahan` masih hardcoded di file Blade PHP — setiap perubahan teks, judul, atau konten memerlukan seorang developer dan proses deployment.

Dengan CMS ini, admin (atau tim konten OPSI 2026) dapat memperbarui konten landing page langsung dari dashboard admin tanpa menyentuh kode, sehingga riset dapat berjalan dengan konten yang up-to-date setiap saat.

---

## 2. Latar Belakang & Masalah

- **Masalah saat ini:**
  - Semua teks, judul, dan konten di 4 halaman landing page hardcoded di Blade
  - Tim OPSI tidak dapat memperbarui konten tanpa bantuan developer
  - Deployment diperlukan hanya untuk mengubah satu kalimat di landing page
  - Model `ProgramContent` dan tabel `program_contents` sudah dibuat namun tidak digunakan
  - Field yang tersedia: `section`, `key`, `title`, `body`, `icon`, `sort_order`, `is_active`, `updated_by`

- **Dampak jika tidak diselesaikan:**
  - Bottle-neck pada developer untuk setiap perubahan konten sekecil apapun
  - Konten tidak bisa diperbarui seiring perkembangan riset OPSI 2026
  - Resource developer terbuang untuk tugas yang bukan core development
  - Aset database (`program_contents`) terbuang sia-sia

- **Solusi yang diusulkan:**
  - Buat halaman CRUD admin untuk mengelola konten per section dan key
  - Update semua halaman landing page untuk membaca konten dari database
  - Implementasi caching konten untuk performa optimal
  - Seeder awal dengan semua konten existing yang saat ini hardcoded

---

## 3. Tujuan & Metrik Keberhasilan

| Tujuan | Metrik | Target |
|--------|--------|--------|
| Admin bisa update konten tanpa developer | Waktu dari keputusan perubahan ke live | < 10 menit |
| Semua konten landing page berasal dari DB | Jumlah hardcoded content di Blade | 0 (selain struktur HTML) |
| Tidak ada penurunan performa | Waktu load halaman landing page | ≤ kondisi sebelumnya (< 2 detik) |
| Konten terdokumentasi | Jumlah section/key yang terdokumentasi | 100% |

---

## 4. Scope

### In Scope
- CRUD Admin untuk mengelola konten berdasarkan `section` dan `key`
- Halaman landing page yang dimigrasi ke konten dinamis: `/` (beranda), `/tentang`, `/edukasi`, `/pencegahan`
- Field yang dapat diedit: `title`, `body`, `icon`, `sort_order`, `is_active`
- Rich text editor dasar untuk field `body` (bukan full CMS — hanya editor WYSIWYG sederhana)
- Seeder dengan semua konten existing yang saat ini hardcoded
- Caching query konten (via Laravel Cache, TTL: 10 menit)
- Cache invalidation saat admin melakukan update
- Activity log untuk setiap perubahan konten (Spatie Activitylog)
- Preview konten sebelum menyimpan (opsional — jika waktu memungkinkan)

### Out of Scope
- Multi-bahasa CMS (konten saat ini hanya Bahasa Indonesia)
- Upload media/gambar via CMS (gambar tetap via asset file)
- Versioning konten (rollback ke versi sebelumnya)
- Workflow review/approval untuk konten (langsung publish)
- Integrasi headless CMS eksternal (Strapi, Contentful, dsb.)
- Pembuatan halaman baru via CMS (hanya konten halaman existing)
- SEO meta tags management via CMS (scope terpisah)

---

## 5. User Stories

| # | Sebagai | Saya ingin | Sehingga |
|---|---------|------------|----------|
| US-3.1 | Admin | Melihat daftar semua konten yang dikelola, dikelompokkan per section | Saya dapat dengan cepat menemukan konten yang perlu diperbarui |
| US-3.2 | Admin | Mengedit judul dan isi konten pada section tertentu | Saya bisa memperbarui informasi riset di landing page tanpa menunggu developer |
| US-3.3 | Admin | Menggunakan editor teks yang mudah untuk memformat konten `body` | Saya bisa membuat teks tebal, miring, atau daftar tanpa mengerti HTML |
| US-3.4 | Admin | Mengaktifkan atau menonaktifkan item konten tertentu | Saya bisa menyembunyikan konten yang belum siap atau sudah tidak relevan |
| US-3.5 | Admin | Mengatur urutan tampil item konten dalam satu section | Saya bisa mengontrol urutan item yang ditampilkan di halaman publik |
| US-3.6 | Admin | Melihat kapan terakhir konten diperbarui dan oleh siapa | Saya bisa melacak riwayat perubahan dan memastikan konten terverifikasi |
| US-3.7 | Pengunjung | Membaca konten landing page yang selalu terkini | Saya mendapatkan informasi yang akurat tentang program STEP dan riset OPSI |
| US-3.8 | Pengunjung | Melihat halaman yang tetap cepat diload | Saya tidak perlu menunggu lama meski konten diambil dari database |
| US-3.9 | Tim OPSI | Memperbarui konten statistik atau program di landing page secara mandiri | Tim tidak perlu menunggu developer untuk update informasi riset |

---

## 6. Acceptance Criteria

| # | Given | When | Then |
|---|-------|------|------|
| AC-3.1 | Admin sudah login dan membuka `/admin/program-contents` | Halaman dibuka | Daftar konten tampil dikelompokkan per section (beranda, tentang, edukasi, pencegahan), masing-masing menampilkan key, title, status aktif, dan tombol Edit |
| AC-3.2 | Admin mengklik tombol "Edit" pada item konten tertentu | Form edit terbuka | Form menampilkan field `title`, `body` (dengan WYSIWYG editor), `icon`, `sort_order`, dan toggle `is_active` yang sudah terisi dengan nilai existing |
| AC-3.3 | Admin mengubah `title` dan `body` sebuah item konten lalu klik "Simpan" | Form disubmit | Data tersimpan di database, cache section tersebut di-invalidate, halaman publik yang terkait langsung menampilkan konten terbaru |
| AC-3.4 | Admin mengubah konten section `beranda` | Perubahan disimpan | Halaman `/` (beranda) menampilkan konten baru dalam < 30 detik (setelah cache expire/invalidated) |
| AC-3.5 | Admin menonaktifkan item konten | Toggle `is_active` di-set ke false | Item tersebut **tidak** ditampilkan di halaman publik; item lain di section yang sama tetap tampil |
| AC-3.6 | Admin mengakses form edit | Halaman form terbuka | Field `updated_by` dan `updated_at` dari record sebelumnya terlihat di halaman (bukan di form input — sebagai informasi saja) |
| AC-3.7 | Admin menyimpan perubahan konten | Perubahan berhasil disimpan | Spatie Activitylog mencatat: subject (ProgramContent), jenis aksi (updated), user yang melakukan, dan field yang berubah |
| AC-3.8 | Database tidak memiliki data untuk section `edukasi` | Pengunjung membuka `/edukasi` | Halaman tetap tampil dengan konten fallback/placeholder yang informatif, tidak error 500 |
| AC-3.9 | Pengunjung membuka halaman `/` | Halaman dirender | Semua konten (hero title, deskripsi program, dsb.) berasal dari database — tidak ada teks hardcoded di Blade |
| AC-3.10 | Admin melakukan 2 edit berturut-turut pada item yang sama | Kedua perubahan disimpan | `updated_at` menunjukkan waktu edit terbaru; `updated_by` menunjukkan user yang terakhir melakukan edit |

---

## 7. Alur Utama (Happy Path)

### Alur Admin Memperbarui Konten Landing Page
1. Admin login dan membuka sidebar, klik "Konten Landing Page"
2. Halaman `/admin/program-contents` terbuka — konten ditampilkan per section dalam accordion atau tab
3. Admin klik section "Beranda", menemukan item dengan key `hero_title`
4. Admin klik tombol "Edit" pada item `hero_title`
5. Form edit terbuka dengan nilai `title` dan `body` existing
6. Admin mengubah teks `title`, menggunakan toolbar WYSIWYG untuk memformat `body`
7. Admin klik "Simpan"
8. Sistem menyimpan ke database, men-set `updated_by = auth()->id()`, `updated_at = now()`
9. Cache untuk section `beranda` di-invalidate
10. Admin melihat notifikasi sukses "Konten berhasil diperbarui"
11. Admin buka tab baru, membuka halaman `/` — konten hero sudah berubah

### Alur Pengunjung Membaca Landing Page
1. Pengunjung membuka `/` (beranda)
2. Controller memanggil `ProgramContentService::getSection('beranda')`
3. Service mengecek cache `program_contents.beranda`
4. Jika cache hit: return dari cache (respons cepat)
5. Jika cache miss: query database WHERE section = 'beranda' AND is_active = true ORDER BY sort_order
6. Hasil di-cache dengan TTL 10 menit
7. Data di-pass ke view sebagai variabel koleksi
8. View merender konten dari variabel (bukan hardcoded)

---

## 8. Business Rules

- **BR-3.1:** `section` dan `key` adalah kombinasi yang unik — tidak boleh ada duplikasi
- **BR-3.2:** Admin tidak bisa membuat section baru via CMS (hanya bisa mengedit item dalam section yang sudah ada) — untuk menambah section baru harus melalui developer + seeder
- **BR-3.3:** `key` adalah identifier tetap dan tidak bisa diubah via CMS — hanya bisa diubah oleh developer
- **BR-3.4:** Setiap kali konten disimpan, cache section yang bersangkutan harus di-invalidate
- **BR-3.5:** Field `updated_by` diisi otomatis dengan ID user yang sedang login
- **BR-3.6:** Item dengan `is_active = false` tidak ditampilkan di halaman publik
- **BR-3.7:** Jika seluruh item dalam satu section nonaktif, halaman publik menampilkan fallback placeholder yang informatif (tidak error)
- **BR-3.8:** Input field `body` yang menggunakan WYSIWYG harus di-sanitasi untuk mencegah XSS sebelum disimpan ke database (whitelist tag HTML: p, br, strong, em, ul, ol, li, h2, h3, a)
- **BR-3.9:** Icon field menerima nama class icon (misal: Bootstrap Icon atau Font Awesome class name) — bukan upload file
- **BR-3.10:** Hanya admin dengan role `admin` yang bisa mengakses CRUD CMS

---

## 9. Data Requirements

| Field | Tipe | Required | Validasi | Keterangan |
|-------|------|----------|----------|------------|
| `id` | bigint unsigned | Auto | — | PK auto-increment |
| `section` | varchar(100) | Ya | max:100, format: lowercase_snake | Grup konten: beranda, tentang, edukasi, pencegahan |
| `key` | varchar(100) | Ya | max:100, format: lowercase_snake, unique per section | Identifier item: hero_title, hero_desc, dll |
| `title` | varchar(500) | Ya | max:500 | Judul item konten |
| `body` | text | Tidak | Sanitasi HTML whitelist | Isi konten lengkap (HTML yang disanitasi) |
| `icon` | varchar(100) | Tidak | max:100 | Class icon (opsional, untuk kartu fitur) |
| `sort_order` | int | Ya | min:0, default:0 | Urutan tampil dalam section |
| `is_active` | boolean | Ya | true/false, default:true | Status tampil di publik |
| `updated_by` | bigint unsigned | Auto | FK ke users.id | User yang terakhir mengedit |
| `created_at` | timestamp | Auto | — | — |
| `updated_at` | timestamp | Auto | — | — |

### Daftar Section & Key yang Akan Di-Seed

| Section | Key | Deskripsi |
|---------|-----|-----------|
| `beranda` | `hero_title` | Judul utama hero section |
| `beranda` | `hero_subtitle` | Subjudul hero section |
| `beranda` | `hero_cta` | Teks tombol CTA utama |
| `beranda` | `about_title` | Judul section tentang STEP |
| `beranda` | `about_body` | Deskripsi program STEP |
| `beranda` | `stats_*` | Item statistik (jika ada) |
| `tentang` | `research_title` | Judul halaman tentang |
| `tentang` | `research_body` | Deskripsi riset |
| `tentang` | `team_*` | Info tim OPSI |
| `edukasi` | `edu_intro` | Intro halaman edukasi |
| `edukasi` | `edu_item_*` | Item-item konten edukasi |
| `pencegahan` | `prevention_intro` | Intro halaman pencegahan |
| `pencegahan` | `prevention_item_*` | Tips pencegahan |

> Daftar lengkap key ditentukan saat proses seeding berdasarkan audit konten Blade existing.

---

## 10. Non-Functional Requirements

- **Performa:**
  - Konten landing page harus di-cache (Laravel Cache, TTL 10 menit)
  - Cache miss tidak boleh menyebabkan response time > 2 detik
  - Cache key format: `program_contents.{section}`
  - Invalidate cache saat ada update pada section yang bersangkutan

- **Keamanan:**
  - Field `body` WAJIB disanitasi menggunakan HTML Purifier atau Laravel Purify sebelum disimpan
  - Tag HTML yang diizinkan: `p`, `br`, `strong`, `em`, `ul`, `ol`, `li`, `h2`, `h3`, `a[href]`
  - Semua output di Blade menggunakan `{!! !!}` hanya untuk konten `body` yang sudah disanitasi; field lain menggunakan `{{ }}`
  - Route admin dilindungi middleware `role:admin`

- **Privasi Data:**
  - Tidak ada data pribadi dalam konten landing page
  - `updated_by` (user ID) hanya ditampilkan ke admin, tidak ke publik

- **Skalabilitas:**
  - Desain service harus memungkinkan penambahan section baru di masa depan tanpa mengubah struktur CMS

---

## 11. Dependencies

- **Hard dependency:**
  - **PRD-001** harus selesai — halaman admin CMS menggunakan `admin-layout.blade.php`
- **Soft dependency:**
  - **PRD-002** dapat dikerjakan paralel (tidak ada ketergantungan langsung)
- **Infrastruktur existing:**
  - Tabel `program_contents` (sudah ada)
  - Model `ProgramContent` (sudah ada)
  - Spatie Activitylog (sudah terpasang)
  - Laravel Cache (sudah aktif)

---

## 12. Estimasi & Timeline

| Layer | Task Utama | Estimasi |
|-------|------------|----------|
| **Database** | Audit schema `program_contents`, buat seeder dari konten Blade existing | 3 jam |
| **Backend** | `ProgramContentController` (index, edit, update, toggleActive) | 3 jam |
| **Backend** | `ProgramContentService` dengan caching + cache invalidation | 2 jam |
| **Backend** | `UpdateProgramContentRequest` validasi + sanitasi | 1 jam |
| **Backend** | Route group `admin/program-contents` | 0.5 jam |
| **Backend** | Update semua controller halaman publik untuk pakai service | 2 jam |
| **Frontend** | Halaman index CMS (tabel per section, filter) | 3 jam |
| **Frontend** | Form edit dengan WYSIWYG editor (TinyMCE atau Quill) | 4 jam |
| **Frontend** | Update 4 halaman landing page ke konten dinamis | 4 jam |
| **Frontend** | Fallback/placeholder jika konten tidak ada | 1 jam |
| **Testing** | Uji semua halaman publik + CRUD admin + cache behavior | 4 jam |
| **Total** | | **27.5 jam (~3.5 hari kerja)** |

> Assigned: Database → Kang Eka | Backend → Kang Bayu | Frontend → Teh Ayu | Testing → Kang Farhan

---

## 13. Risks & Mitigasi

| Risk | Likelihood | Impact | Score | Level | Mitigasi |
|------|-----------|--------|-------|-------|----------|
| Seeder salah mengkategorikan konten ke section yang salah | 3 | 3 | 9 | Medium | Audit manual semua halaman Blade sebelum membuat seeder; review bersama tim OPSI |
| XSS melalui field `body` yang menggunakan WYSIWYG | 3 | 4 | 12 | Medium | Wajib implementasi HTML sanitasi sebelum simpan; gunakan whitelist tag yang ketat |
| Cache tidak ter-invalidate saat konten diupdate | 2 | 3 | 6 | Medium | Unit test untuk cache invalidation; tambahkan `Cache::forget()` di setiap update |
| Kegagalan WYSIWYG library (JS error) | 2 | 2 | 4 | Low | Fallback ke textarea biasa jika WYSIWYG gagal load |
| Seeding konten tidak sesuai kebutuhan tim OPSI | 3 | 2 | 6 | Medium | Libatkan tim OPSI dalam review seeder sebelum production |
| Schema tabel `program_contents` berbeda dari ekspektasi | 2 | 3 | 6 | Medium | Audit schema lebih dulu sebelum coding |

---

## 14. Wireframe / Mockup Reference

### Admin CMS Index (Program Contents)
```
+------------------------------------------------------+
| Konten Landing Page                    [Filter: All ▼]|
+------------------------------------------------------+
| [SECTION: BERANDA]                                   |
|  Key            | Title        | Status | Aksi        |
|  hero_title     | "Judul Utama"| ✅ Aktif | [Edit]    |
|  hero_subtitle  | "Subjudul"   | ✅ Aktif | [Edit]    |
|  about_title    | "Tentang"    | ✅ Aktif | [Edit]    |
+------------------------------------------------------+
| [SECTION: TENTANG]                                   |
|  research_title | "Riset STEP" | ✅ Aktif | [Edit]    |
|  research_body  | "Deskripsi.."| ✅ Aktif | [Edit]    |
+------------------------------------------------------+
```

### Admin Form Edit Konten
```
+------------------------------------------------------+
| Edit Konten: [hero_title] — Section: beranda         |
+------------------------------------------------------+
| Title:  [                                          ]  |
|                                                      |
| Body:   [WYSIWYG EDITOR                           ]  |
|         [B I U | H2 H3 | List | Link             ]  |
|         [                                         ]  |
|                                                      |
| Icon:   [bi-star                               ]     |
| Urutan: [1]    Status: [✅ Aktif]                    |
|                                                      |
| Terakhir diperbarui: 2026-07-20 oleh Admin           |
|                                                      |
|              [Batal]  [Simpan Perubahan]            |
+------------------------------------------------------+
```

---

## 15. State Diagram

```
ProgramContent States:
  [is_active = true]  → Ditampilkan di halaman publik sesuai sort_order
  [is_active = false] → Disembunyikan dari halaman publik

Cache Lifecycle:
  [DB Update] → Cache.forget(section) → [Cache Miss] → DB Query → [Cache Set TTL:10m]
                                      ↑_________________________↓
                                        [Cache Hit] ← [Next Request]
```

---

## 16. Database Schema Changes

### Tabel Existing: `program_contents`
> Audit terlebih dahulu. Skema yang diharapkan:

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | bigint unsigned PK | — |
| `section` | varchar(100) | Grup section |
| `key` | varchar(100) | Identifier item |
| `title` | varchar(500) | Judul item |
| `body` | text nullable | Konten HTML tersanitasi |
| `icon` | varchar(100) nullable | Class icon |
| `sort_order` | int default 0 | Urutan |
| `is_active` | boolean default true | Status |
| `updated_by` | bigint unsigned nullable FK | User yang update |
| `created_at` | timestamp | — |
| `updated_at` | timestamp | — |

> **Index yang direkomendasikan:** Index composite pada `(section, is_active, sort_order)` untuk query di halaman publik.

---

## 17. Migration Plan

1. Audit schema `program_contents` yang existing
2. Tambahkan index jika belum ada: `INDEX(section, is_active, sort_order)`
3. Audit semua konten hardcoded di 4 file Blade landing page
4. Buat `ProgramContentSeeder` dengan semua konten existing
5. Jalankan seeder di development, verifikasi output di halaman publik
6. Ganti Blade hardcoded dengan pembacaan dari database
7. Test halaman publik sebelum deploy

---

## 18. Rollback Strategy

- **Trigger rollback:** Halaman publik error karena query CMS gagal
- **Langkah rollback:**
  1. Kembalikan file Blade ke versi hardcoded sebelumnya via Git
  2. `php artisan view:clear && php artisan cache:clear`
  3. Verifikasi halaman kembali berfungsi
- **Estimasi waktu rollback:** < 10 menit

---

## 19. Documentation Updates Required

- [ ] Panduan admin: cara menggunakan CMS untuk edit konten landing page
- [ ] Dokumentasi daftar section dan key yang tersedia
- [ ] Panduan sanitasi HTML — tag apa yang diizinkan

---

## Changelog

| Versi | Tanggal | Perubahan | Oleh |
|-------|---------|-----------|------|
| 1.0 | 2026-07-23 | Initial draft — Program Content CMS | Kang Dadang |

---

## Approval

| Role | Nama | Status | Tanggal |
|------|------|--------|---------|
| Product Owner | Mas Lutfi | Pending | — |
| PM Manager | Sophia | Pending | — |
| Tech Lead | — | Pending | — |

---

*PRD ini adalah dokumen hidup. Setiap perubahan setelah approval harus melalui proses versioning dan re-approval.*
