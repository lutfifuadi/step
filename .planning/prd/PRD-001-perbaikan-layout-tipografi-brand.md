# PRD-001: Perbaikan Layout, Tipografi & Konsistensi Brand (UI/UX Overhaul)

| Field | Detail |
|-------|--------|
| **PRD ID** | PRD-001 |
| **Versi** | 1.0 |
| **Status** | Approved |
| **Penulis** | Kang Dadang (PRD Specialist) |
| **Tanggal** | 2026-07-23 |
| **Prioritas** | Critical |
| **Target Release** | Sprint 1 |
| **RICE Score** | 480 |
| **Approved By** | Mas Lutfi (Product Owner) & Sophia (PM) |
| **Approval Date** | 2026-07-24 |

---

## Kalkulasi RICE Score

| Parameter | Nilai | Keterangan |
|-----------|-------|------------|
| Reach | 200 user/bulan | Semua pengguna (remaja, admin, peneliti) terdampak |
| Impact | 2.0 — High | Inkonsistensi visual menurunkan kepercayaan & usabilitas |
| Confidence | 80% | Masalah sudah teridentifikasi dari analisis UI/UX |
| Effort | 4 person-weeks | Estimasi total semua layer |
| **RICE Score** | **(200 × 2.0 × 0.8) / 4 = 80** | *Prioritas Tinggi* |

> Catatan: RICE 80 masuk kategori Medium-High. Namun karena ini adalah **fondasi UX** — semua PRD lain bergantung pada konsistensi layout ini — maka prioritas di-upgrade ke **Critical**.

---

## 1. Ringkasan

PRD ini mencakup perbaikan menyeluruh terhadap layout, tipografi, dan konsistensi brand visual Aplikasi STEP. Saat ini terdapat tiga masalah utama: (1) `blankLayout.blade.php` dimodifikasi secara paksa sehingga navbar bocor ke halaman login/register dan mendorong konten 60–70px; (2) font yang digunakan berbeda antara halaman publik (Poppins) dan halaman ekspresi (Public Sans), menyebabkan flicker visual saat berpindah halaman; dan (3) warna brand STEP (Teal `#003d33`, Amber `#f59e0b`, Cream `#fdf6ee`) tidak diterapkan secara konsisten.

Perbaikan ini bersifat **foundational** — semua fitur lain (PRD-002 sampai PRD-005) akan mengikuti panduan brand yang dihasilkan dari PRD ini.

---

## 2. Latar Belakang & Masalah

- **Masalah saat ini:**
  - `blankLayout.blade.php` disisipkan navbar secara hardcoded, menyebabkan halaman login/register terdorong 60–70px
  - Dashboard admin dan peneliti menggunakan layout publik (front) tanpa sidebar navigasi — admin tidak bisa berpindah antar halaman
  - Font Poppins (halaman publik) vs. Public Sans (halaman ekspresi) tidak konsisten, menimbulkan *font flicker*
  - Warna brand STEP tidak diterapkan secara seragam di halaman ekspresi dan dashboard
  - Footer halaman ekspresi masih berisi konten dummy dari template Vuexy Pixinvent
  - Navbar publik tidak mendeteksi status login (`@auth/@guest`) — tombol Login selalu muncul meski admin sudah login

- **Dampak jika tidak diselesaikan:**
  - Kepercayaan remaja terhadap aplikasi menurun (tampilan tidak profesional)
  - Admin tidak produktif karena tidak bisa navigasi antar fitur moderasi
  - Risiko scope creep jika setiap PRD baru harus "fix sendiri" inconsistency layout
  - Brand OPSI 2026 terkesan tidak serius di hadapan responden riset

- **Solusi yang diusulkan:**
  - Pisahkan layout menjadi 3 jenis yang bersih: `public-layout`, `auth-layout`, `admin-layout`
  - Standarisasi font ke **Poppins** untuk seluruh aplikasi
  - Buat CSS variables untuk token warna brand STEP
  - Perbaiki navbar dengan deteksi `@auth/@guest`
  - Buat sidebar navigasi untuk admin dan peneliti
  - Bersihkan footer dari konten dummy

---

## 3. Tujuan & Metrik Keberhasilan

| Tujuan | Metrik | Target |
|--------|--------|--------|
| Menghilangkan font flicker antar halaman | Jumlah font family yang dipakai | ≤ 1 font family (Poppins) |
| Memperbaiki layout bocor | Offset layout login/register | 0px (tidak ada pergeseran) |
| Konsistensi warna brand | % halaman yang menggunakan token warna STEP | 100% |
| Navigasi admin berfungsi | Admin dapat mengakses semua menu dari sidebar | 100% menu tersedia |
| Navbar cerdas | Tombol Login tidak muncul saat user sudah login | 0 false positive |
| Footer bersih | Tidak ada konten dummy Vuexy di footer halaman ekspresi | 0 dummy content |

---

## 4. Scope

### In Scope
- Pembuatan/refaktor 3 layout template: `public-layout.blade.php`, `auth-layout.blade.php`, `admin-layout.blade.php`
- Standarisasi font ke Poppins untuk seluruh halaman
- Pembuatan CSS custom properties (variables) untuk token warna brand STEP
- Perbaikan navbar publik dengan kondisi `@auth/@guest`
- Pembuatan sidebar navigasi untuk role Admin dan Peneliti
- Perbaikan footer halaman ekspresi (hapus konten dummy Vuexy)
- Migrasi semua halaman existing ke layout yang sesuai
- Responsive layout (mobile-first, min. breakpoint: 375px, 768px, 1024px)

### Out of Scope
- Penggantian template Vuexy Bootstrap secara menyeluruh (tidak diganti, hanya dirapikan)
- Desain ulang komponen form ekspresi (dibahas di PRD lain)
- Dark mode (tidak dalam scope proyek riset ini)
- Perubahan logo atau identitas brand (aset sudah ada, digunakan apa adanya)
- Animasi dan transisi halaman kompleks

---

## 5. User Stories

| # | Sebagai | Saya ingin | Sehingga |
|---|---------|------------|----------|
| US-1.1 | Remaja pengunjung | Melihat tampilan yang konsisten saat berpindah halaman | Saya merasa aplikasi ini profesional dan terpercaya untuk mencurahkan perasaan |
| US-1.2 | Admin | Memiliki sidebar navigasi di dashboard | Saya bisa dengan cepat berpindah antara halaman moderasi, daftar ekspresi, dan pengaturan |
| US-1.3 | Peneliti | Mengakses dashboard dengan layout yang terpisah dari halaman publik | Saya tidak bingung membedakan area kerja peneliti dengan halaman publik |
| US-1.4 | Pengunjung yang belum login | Melihat tombol Login di navbar | Saya tahu cara masuk ke sistem |
| US-1.5 | Admin yang sudah login | Tidak melihat tombol Login di navbar publik | Saya tidak diperlihatkan opsi yang tidak relevan |
| US-1.6 | Admin | Melihat footer yang relevan di halaman ekspresi | Halaman terlihat profesional dan tidak membingungkan remaja dengan konten dummy |
| US-1.7 | Developer / semua agent teknis | Memiliki panduan brand (token warna + font) yang terdokumentasi | Setiap halaman baru yang dibuat langsung mengikuti standar visual STEP |

---

## 6. Acceptance Criteria

| # | Given | When | Then |
|---|-------|------|------|
| AC-1.1 | Seorang remaja mengunjungi halaman beranda | Mereka berpindah ke halaman `/ekspresi` | Font tetap Poppins tanpa flicker — tidak ada pergantian font yang terlihat |
| AC-1.2 | Seorang pengunjung membuka halaman `/login` | Halaman dirender | Konten form login tidak terdorong ke bawah — offset dari navbar = 0px |
| AC-1.3 | Admin sudah login dan membuka halaman `/admin/dashboard` | Halaman dirender | Sidebar navigasi muncul dengan menu: Dashboard, Ekspresi, Kontak Konselor, Konten Landing Page |
| AC-1.4 | Admin berada di sidebar dan mengklik menu "Daftar Ekspresi" | Klik menu | Halaman berpindah ke `/admin/expressions` tanpa full page reload yang membutuhkan navigasi manual via URL |
| AC-1.5 | Pengunjung belum login membuka halaman beranda | Navbar dirender | Tombol "Login" muncul di navbar |
| AC-1.6 | Admin sudah login membuka halaman beranda | Navbar dirender | Tombol "Login" **tidak** muncul; sebagai gantinya muncul nama user atau dropdown profil |
| AC-1.7 | Admin membuka halaman `/ekspresi` | Halaman dirender | Footer hanya menampilkan informasi relevan STEP (kontak darurat, copyright) — tidak ada konten dummy Vuexy |
| AC-1.8 | Developer menginspeksi CSS aplikasi | Membuka browser DevTools | Token warna `--step-teal: #003d33`, `--step-amber: #f59e0b`, `--step-cream: #fdf6ee` terdefinisi di `:root` |
| AC-1.9 | Penguji membuka aplikasi di mobile 375px | Semua halaman dibuka | Layout tidak overflow horizontal — tidak ada scrollbar horizontal |
| AC-1.10 | Peneliti login dan membuka `/researcher/dashboard` | Halaman dirender | Layout menggunakan `admin-layout` dengan sidebar yang menampilkan menu Peneliti |

---

## 7. Alur Utama (Happy Path)

### Alur Navigasi Admin (setelah login)
1. Admin berhasil login via `/login`
2. Sistem mendeteksi role `admin` dan redirect ke `/admin/dashboard`
3. Halaman `/admin/dashboard` dirender menggunakan `admin-layout.blade.php`
4. Sidebar kiri muncul dengan menu yang sesuai role admin
5. Admin mengklik menu "Daftar Ekspresi" di sidebar
6. Halaman `/admin/expressions` dirender — sidebar tetap visible
7. Admin dapat menekan tombol back browser atau klik menu lain — sidebar konsisten di semua halaman admin

### Alur Pengunjung ke Halaman Ekspresi
1. Pengunjung membuka `/` (beranda)
2. Navbar publik tampil — jika belum login: tombol "Login" muncul; jika sudah login: tombol "Login" diganti profil/nama
3. Pengunjung klik "Kirim Ekspresi" di navbar atau hero section
4. Halaman `/ekspresi` dirender menggunakan `public-layout.blade.php` (bukan `blankLayout`)
5. Font Poppins load dari `<head>` yang sama — tidak ada flicker
6. Footer menampilkan kontak konselor dan copyright STEP

---

## 8. Business Rules

- **BR-1.1:** Halaman dalam route group `admin/*` dan `researcher/*` WAJIB menggunakan `admin-layout.blade.php`
- **BR-1.2:** Halaman dalam route group autentikasi (`/login`, `/register`) WAJIB menggunakan `auth-layout.blade.php` — layout ini tidak memiliki navbar publik
- **BR-1.3:** Semua halaman publik (`/`, `/tentang`, `/edukasi`, `/pencegahan`, `/ekspresi`) WAJIB menggunakan `public-layout.blade.php`
- **BR-1.4:** `blankLayout.blade.php` tidak boleh dimodifikasi dengan hardcoded navbar — dikembalikan ke kondisi blank murni (untuk template internal Vuexy jika diperlukan)
- **BR-1.5:** Navbar publik WAJIB menggunakan directive `@auth` dan `@guest` untuk menampilkan konten yang sesuai
- **BR-1.6:** Token warna brand STEP WAJIB didefinisikan sebagai CSS Custom Properties di file CSS global, bukan hardcoded di setiap komponen
- **BR-1.7:** Font Poppins di-load satu kali di layout induk — tidak boleh ada import font duplikat di halaman anak
- **BR-1.8:** Sidebar admin WAJIB menampilkan item menu berdasarkan role: Admin melihat semua menu, Peneliti hanya melihat menu Peneliti

---

## 9. Data Requirements

| Field/Element | Tipe | Diperlukan | Validasi | Keterangan |
|---------------|------|------------|----------|------------|
| `user.name` | string | Ya | Diambil dari Auth | Ditampilkan di navbar/sidebar saat user login |
| `user.roles` | array | Ya | Spatie Permission | Menentukan menu sidebar yang ditampilkan |
| Token warna brand | CSS variable | Ya | Format hex valid | `--step-teal`, `--step-amber`, `--step-cream` |
| Font Poppins | Google Fonts URL | Ya | — | Di-load via `<link>` di `<head>` layout |
| Sidebar menu items | array konfigurasi | Ya | Role-based | Dikonfigurasi di file/komponen layout |
| Footer content | Static | Ya | — | Kontak darurat + copyright |

---

## 10. Non-Functional Requirements

- **Performa:**
  - Font Poppins harus menggunakan `display=swap` agar tidak memblokir render
  - CSS total (setelah minifikasi) tidak boleh bertambah lebih dari 50KB dari kondisi saat ini
  - First Contentful Paint (FCP) ≤ 2.5 detik pada koneksi 3G reguler

- **Keamanan:**
  - Tidak ada informasi sensitif yang bocor ke layout publik dari context admin
  - Middleware `role:admin` tetap aktif — layout tidak mem-bypass autentikasi

- **Kompatibilitas:**
  - Browser: Chrome 110+, Firefox 110+, Safari 15+, Edge 110+
  - Perangkat mobile: iOS Safari 15+, Android Chrome 110+
  - Resolusi minimum: 375px (mobile), 768px (tablet), 1280px (desktop)

- **Aksesibilitas:**
  - Sidebar harus navigable via keyboard (Tab, Enter, Escape untuk close di mobile)
  - Kontras warna minimal WCAG AA (4.5:1 untuk teks normal)

- **Privasi Data:**
  - Nama user yang ditampilkan di navbar menggunakan nama tampilan (bukan email atau data sensitif lainnya)

---

## 11. Dependencies

- **Prerequisite:** Tidak ada — PRD ini adalah fondasi, harus dikerjakan **pertama**
- **Dependen pada PRD ini:**
  - PRD-002: Kontak Konselor akan menggunakan `admin-layout` dan `public-layout` yang dihasilkan PRD ini
  - PRD-003: CMS Landing Page akan menggunakan `admin-layout` dan `public-layout` yang dihasilkan PRD ini
  - PRD-004: Dashboard Ekspor Data akan menggunakan `admin-layout` untuk sidebar peneliti
  - PRD-005: Sidebar Admin & Navbar Fix adalah **bagian dari PRD ini** — tidak ada dependency terpisah

---

## 12. Estimasi & Timeline

| Layer | Task Utama | Estimasi |
|-------|------------|----------|
| **Frontend** | Buat `public-layout.blade.php` baru yang bersih | 4 jam |
| **Frontend** | Buat `auth-layout.blade.php` (tanpa navbar) | 2 jam |
| **Frontend** | Buat `admin-layout.blade.php` dengan sidebar | 6 jam |
| **Frontend** | Standarisasi font Poppins + CSS token warna | 2 jam |
| **Frontend** | Perbaikan navbar publik (`@auth/@guest`) | 2 jam |
| **Frontend** | Perbaikan footer halaman ekspresi | 1 jam |
| **Frontend** | Migrasi semua halaman existing ke layout yang benar | 4 jam |
| **Backend** | Pastikan middleware & route group konsisten dengan layout | 2 jam |
| **Testing** | Uji semua halaman di 3 breakpoint, 2 role, 3 browser | 4 jam |
| **Total** | | **27 jam (~4 hari kerja)** |

> Assigned: Frontend → Teh Ayu | Backend → Kang Bayu | Testing → Kang Farhan

---

## 13. Risks & Mitigasi

| Risk | Likelihood | Impact | Score | Level | Mitigasi |
|------|-----------|--------|-------|-------|----------|
| Migrasi layout merusak halaman existing yang tidak diuji | 3 | 4 | 12 | Medium | Buat checklist semua halaman, uji satu per satu sebelum deploy |
| Vuexy template punya CSS global yang override token warna baru | 4 | 3 | 12 | Medium | Gunakan selector spesifisitas lebih tinggi atau `!important` terakhir sebagai fallback |
| Sidebar admin conflict dengan layout Jetstream bawaan | 3 | 3 | 9 | Medium | Audit Jetstream layout files terlebih dahulu; override hanya yang diperlukan |
| Font Poppins gagal load (CDN Google Fonts diblokir) | 2 | 2 | 4 | Low | Host font lokal sebagai fallback via `npm run dev` / asset kompilasi |
| Refaktor layout memakan waktu lebih dari estimasi | 3 | 2 | 6 | Medium | Kerjakan layout inti dulu (admin-layout), halaman sekunder bisa iterasi |

---

## 14. Wireframe / Mockup Reference

### Admin Layout Struktur
```
+--------------------------------------------------+
| [LOGO STEP]     Sidebar    | [Main Content Area]  |
|                             |                      |
| 📊 Dashboard                | <halaman aktif>      |
| 📝 Daftar Ekspresi         |                      |
| 📞 Kontak Konselor         |                      |
| 📄 Konten Landing Page     |                      |
| ──────────────              |                      |
| 👤 [Nama Admin]  [Logout]  |                      |
+--------------------------------------------------+
```

### Public Layout Struktur
```
+--------------------------------------------------+
| [Navbar: Logo | Menu | Login/Profil]              |
|--------------------------------------------------|
| [Hero / Konten Halaman]                          |
|--------------------------------------------------|
| [Footer: Kontak Darurat | Copyright STEP]        |
+--------------------------------------------------+
```

### Auth Layout Struktur
```
+--------------------------------------------------+
| [Centered Card: Form Login/Register]              |
| [Logo STEP di atas form]                          |
+--------------------------------------------------+
```

---

## 15. State Diagram

```
[Kunjungi URL]
    |
    +-- Route publik (/, /tentang, /ekspresi, dll)
    |       → public-layout.blade.php
    |           Navbar: @guest → Login | @auth → Profil/Dropdown
    |
    +-- Route auth (/login, /register)
    |       → auth-layout.blade.php
    |           Tidak ada navbar publik
    |
    +-- Route admin (admin/*)
    |       → admin-layout.blade.php (middleware: role:admin)
    |           Sidebar: Dashboard, Ekspresi, Konselor, CMS
    |
    +-- Route peneliti (researcher/*)
            → admin-layout.blade.php (middleware: role_or_permission:researcher|admin)
                Sidebar: Dashboard, Ekspor Data
```

---

## 16. Database Schema Changes

Tidak ada perubahan database untuk PRD ini. Semua perubahan bersifat presentasi (Blade/CSS).

---

## 17. Migration Plan

Tidak diperlukan migrasi database. Migrasi yang diperlukan adalah **migrasi template Blade**:

1. Backup semua file Blade yang akan diubah
2. Buat layout baru terlebih dahulu (tidak merusak yang existing)
3. Update layout reference satu halaman sekaligus (dimulai dari halaman admin)
4. Uji setiap halaman setelah update sebelum lanjut ke berikutnya
5. Hapus referensi ke `blankLayout.blade.php` yang telah dimodifikasi

---

## 18. Rollback Strategy

- **Trigger rollback:** Layout baru merusak fungsi form pengiriman ekspresi atau akses admin
- **Langkah rollback:**
  1. Revert file Blade ke versi sebelumnya via Git
  2. Clear view cache: `php artisan view:clear`
  3. Verifikasi halaman kembali berfungsi normal
- **Estimasi waktu rollback:** < 10 menit (via `git revert`)
- **Data recovery:** Tidak diperlukan — tidak ada perubahan data

---

## 19. Monitoring & Alerting

- **Metrik yang dimonitor:** Error 500 pada halaman yang dimigrasikan, bounce rate halaman ekspresi
- **Alert threshold:** Error 500 lebih dari 3x dalam 5 menit → notifikasi ke developer
- **On-call:** Kang Bayu (backend), Teh Ayu (frontend)

---

## 20. Documentation Updates Required

- [ ] Update README dengan panduan layout system baru
- [ ] Buat `DESIGN_SYSTEM.md` yang mendokumentasikan token warna dan font STEP
- [ ] Update panduan kontribusi (jika ada) agar developer baru tahu layout mana yang harus dipakai

---

## Changelog

| Versi | Tanggal | Perubahan | Oleh |
|-------|---------|-----------|------|
| 1.0 | 2026-07-23 | Initial draft berdasarkan analisis UI/UX Aplikasi STEP | Kang Dadang |

---

## Approval

| Role | Nama | Status | Tanggal |
|------|------|--------|---------|
| Product Owner | Mas Lutfi | Pending | — |
| PM Manager | Sophia | Pending | — |
| Tech Lead | — | Pending | — |

---

*PRD ini adalah dokumen hidup. Setiap perubahan setelah approval harus melalui proses versioning dan re-approval.*
