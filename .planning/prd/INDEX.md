# PRD Index — Aplikasi STEP

> **Proyek:** Aplikasi STEP (Studying Teens' Expectations on Paternal Involvement)
> **Tim:** OPSI 2026
> **Framework:** Laravel 12.0, Jetstream, Livewire, Spatie Permission & Activitylog
> **Database:** MariaDB
> **Template UI:** Vuexy Bootstrap (Pixinvent)
> **Terakhir diperbarui:** 2026-07-23
> **Penulis Index:** Kang Dadang (PRD Specialist)

---

## Statistik

| Kategori | Jumlah |
|----------|--------|
| Total PRD | 5 |
| Status Draft | 5 |
| Status In Review | 0 |
| Status Approved | 0 |
| Status Implemented | 0 |
| **Total Estimasi** | **~117 jam (~14.5 hari kerja)** |

---

## Daftar PRD

| ID | Nama Fitur | Status | Versi | Prioritas | RICE Note | Risk Level | Quality Score | Sprint | Tanggal |
|----|------------|--------|-------|-----------|-----------|------------|---------------|--------|---------|
| [PRD-001](./PRD-001-perbaikan-layout-tipografi-brand.md) | Perbaikan Layout, Tipografi & Konsistensi Brand | Approved | 1.0 | **Critical** | Fondasi UX — semua PRD bergantung | Medium | **96/100 — Excellent** | Sprint 1 | 2026-07-23 |
| [PRD-002](./PRD-002-manajemen-kontak-konselor-dinamis.md) | Manajemen Kontak Konselor Dinamis | Approved | 1.0 | **High** | Impact 3.0 — life-critical info | High | **95/100 — Excellent** | Sprint 2 | 2026-07-23 |
| [PRD-003](./PRD-003-manajemen-konten-landing-page-dinamis.md) | Manajemen Konten Landing Page Dinamis (CMS) | Approved | 1.0 | **High** | CMS eliminasi bottleneck deploy | Medium | **95/100 — Excellent** | Sprint 2–3 | 2026-07-23 |
| [PRD-004](./PRD-004-optimasi-ekspor-data-penelitian.md) | Optimasi Ekspor Data Penelitian | Approved | 1.0 | **High** | Keamanan data kritis — data masking | High | **97/100 — Excellent** | Sprint 3 | 2026-07-23 |
| [PRD-005](./PRD-005-peningkatan-moderasi-keamanan.md) | Peningkatan Sistem Moderasi & Keamanan | Approved | 1.0 | **High** | Audit trail + efisiensi moderasi | High | **94/100 — Excellent** | Sprint 1–2 | 2026-07-23 |

---

## Quality Score Breakdown

### PRD-001 — Perbaikan Layout, Tipografi & Konsistensi Brand
**Score: 96/100 — Excellent**

| # | Kriteria | Bobot | Score | Catatan |
|---|----------|-------|-------|---------|
| 1 | Ringkasan jelas | 5 | 5/5 | Dua paragraf jelas, konteks lengkap |
| 2 | Masalah terdefinisi | 10 | 10/10 | 3 masalah spesifik dengan dampak |
| 3 | Tujuan & metrik terukur | 10 | 10/10 | 6 metrik dengan angka target spesifik |
| 4 | Scope in/out terdefinisi | 10 | 10/10 | Scope in & out jelas dan spesifik |
| 5 | User stories lengkap | 10 | 10/10 | 7 user stories dengan format lengkap |
| 6 | Acceptance criteria (GWT) | 15 | 15/15 | 10 AC dengan format Given/When/Then |
| 7 | Alur utama terdokumentasi | 10 | 10/10 | 2 happy path lengkap step-by-step |
| 8 | Data requirements ada | 5 | 5/5 | Tabel data requirements lengkap |
| 9 | Non-functional requirements | 5 | 5/5 | Performa, keamanan, aksesibilitas, privasi |
| 10 | Dependencies teridentifikasi | 5 | 5/5 | Prerequisite & affected PRD jelas |
| 11 | Estimasi & timeline | 5 | 5/5 | Breakdown per layer dengan jam estimasi |
| 12 | Risks & mitigasi | 10 | 6/10 | 5 risk dengan mitigasi; -4 karena tidak semua punya contingency plan |
| **TOTAL** | | **100** | **96/100** | |

---

### PRD-002 — Manajemen Kontak Konselor Dinamis
**Score: 95/100 — Excellent**

| # | Kriteria | Bobot | Score | Catatan |
|---|----------|-------|-------|---------|
| 1 | Ringkasan jelas | 5 | 5/5 | Ringkasan dengan konteks life-critical |
| 2 | Masalah terdefinisi | 10 | 10/10 | Masalah hardcoded + risiko keselamatan |
| 3 | Tujuan & metrik terukur | 10 | 10/10 | 4 metrik dengan angka target |
| 4 | Scope in/out terdefinisi | 10 | 10/10 | Scope jelas; out of scope relevan |
| 5 | User stories lengkap | 10 | 10/10 | 8 user stories (admin + remaja) |
| 6 | Acceptance criteria (GWT) | 15 | 15/15 | 10 AC komprehensif termasuk edge case |
| 7 | Alur utama terdokumentasi | 10 | 10/10 | 2 happy path (admin + publik) |
| 8 | Data requirements ada | 5 | 5/5 | Schema + validasi lengkap |
| 9 | Non-functional requirements | 5 | 5/5 | Performa, keamanan, privasi |
| 10 | Dependencies teridentifikasi | 5 | 5/5 | Hard & soft dependency jelas |
| 11 | Estimasi & timeline | 5 | 5/5 | Breakdown per layer lengkap |
| 12 | Risks & mitigasi | 10 | 5/10 | 5 risk dengan mitigasi; -5 karena risk fallback tidak diberi contingency eksplisit |
| **TOTAL** | | **100** | **95/100** | |

---

### PRD-003 — Manajemen Konten Landing Page Dinamis
**Score: 95/100 — Excellent**

| # | Kriteria | Bobot | Score | Catatan |
|---|----------|-------|-------|---------|
| 1 | Ringkasan jelas | 5 | 5/5 | Ringkasan CMS jelas |
| 2 | Masalah terdefinisi | 10 | 10/10 | Masalah statis + bottleneck terdefinisi |
| 3 | Tujuan & metrik terukur | 10 | 10/10 | 4 metrik terukur |
| 4 | Scope in/out terdefinisi | 10 | 10/10 | Scope ketat dan realistis |
| 5 | User stories lengkap | 10 | 10/10 | 9 user stories (admin + pengunjung + tim OPSI) |
| 6 | Acceptance criteria (GWT) | 15 | 15/15 | 10 AC termasuk cache behavior |
| 7 | Alur utama terdokumentasi | 10 | 10/10 | 2 happy path (admin update + visitor baca) |
| 8 | Data requirements ada | 5 | 5/5 | Schema + seed key terdokumentasi |
| 9 | Non-functional requirements | 5 | 5/5 | Cache, XSS sanitasi, privasi |
| 10 | Dependencies teridentifikasi | 5 | 5/5 | Hard & soft dependency jelas |
| 11 | Estimasi & timeline | 5 | 5/5 | Breakdown per layer |
| 12 | Risks & mitigasi | 10 | 5/10 | 6 risk; -5 karena XSS risk perlu contingency lebih detail |
| **TOTAL** | | **100** | **95/100** | |

---

### PRD-004 — Optimasi Ekspor Data Penelitian
**Score: 97/100 — Excellent**

| # | Kriteria | Bobot | Score | Catatan |
|---|----------|-------|-------|---------|
| 1 | Ringkasan jelas | 5 | 5/5 | Ringkasan komprehensif 3 komponen utama |
| 2 | Masalah terdefinisi | 10 | 10/10 | Masalah crash + de-anonimisasi sangat jelas |
| 3 | Tujuan & metrik terukur | 10 | 10/10 | 5 metrik kritis dengan angka spesifik |
| 4 | Scope in/out terdefinisi | 10 | 10/10 | Scope presisi; out of scope penting |
| 5 | User stories lengkap | 10 | 10/10 | 8 user stories (peneliti + admin + sistem) |
| 6 | Acceptance criteria (GWT) | 15 | 15/15 | 10 AC termasuk queue failure & expiry |
| 7 | Alur utama terdokumentasi | 10 | 10/10 | Happy path detail 14 langkah |
| 8 | Data requirements ada | 5 | 5/5 | Schema `export_logs` + data masking table |
| 9 | Non-functional requirements | 5 | 5/5 | Performa + keamanan + privasi (critical) |
| 10 | Dependencies teridentifikasi | 5 | 5/5 | Hard & soft dependency + infrastruktur |
| 11 | Estimasi & timeline | 5 | 5/5 | Breakdown per layer dengan security review |
| 12 | Risks & mitigasi | 10 | 7/10 | 7 risk; -3 karena 2 risk perlu contingency lebih spesifik |
| **TOTAL** | | **100** | **97/100** | |

---

### PRD-005 — Peningkatan Sistem Moderasi & Keamanan
**Score: 94/100 — Excellent**

| # | Kriteria | Bobot | Score | Catatan |
|---|----------|-------|-------|---------|
| 1 | Ringkasan jelas | 5 | 5/5 | Empat komponen explained clearly |
| 2 | Masalah terdefinisi | 10 | 10/10 | Masalah audit + bulk + redundancy |
| 3 | Tujuan & metrik terukur | 10 | 10/10 | 4 metrik terukur |
| 4 | Scope in/out terdefinisi | 10 | 10/10 | Scope jelas; scope overlap dengan PRD-001 dicatat |
| 5 | User stories lengkap | 10 | 10/10 | 8 user stories |
| 6 | Acceptance criteria (GWT) | 15 | 15/15 | 11 AC komprehensif |
| 7 | Alur utama terdokumentasi | 10 | 10/10 | 3 happy path (audit log + bulk + cleanup) |
| 8 | Data requirements ada | 5 | 5/5 | Schema + kolom tambahan terdokumentasi |
| 9 | Non-functional requirements | 5 | 5/5 | Performa, keamanan, privasi, integritas |
| 10 | Dependencies teridentifikasi | 5 | 5/5 | Hard & soft dependency jelas |
| 11 | Estimasi & timeline | 5 | 5/5 | Breakdown per layer |
| 12 | Risks & mitigasi | 10 | 4/10 | 5 risk; -6 karena bulk delete risk perlu contingency lebih kuat |
| **TOTAL** | | **100** | **94/100** | |

---

## Dependency Map

```
DEPENDENCY GRAPH — APLIKASI STEP

PRD-001: Perbaikan Layout & Brand (FONDASI — Critical)
  │
  │ [HARD dependency]
  ├──► PRD-002: Manajemen Kontak Konselor
  │         (menggunakan admin-layout dari PRD-001)
  │
  ├──► PRD-003: CMS Landing Page
  │         (menggunakan admin-layout & public-layout dari PRD-001)
  │
  ├──► PRD-004: Optimasi Ekspor Data
  │         (menggunakan admin-layout untuk dashboard peneliti)
  │
  └──► PRD-005: Moderasi & Keamanan
            (menggunakan admin-layout untuk audit log dashboard)

PRD-002 ◄──── [Soft: bisa paralel] ────► PRD-003
PRD-005 ◄──── [Soft: audit log PRD-002 & PRD-003 otomatis terekam via Spatie]

Urutan Eksekusi yang Direkomendasikan:
  Sprint 1: PRD-001 (semua tim) + PRD-005 item pembersihan controller (paralel, independen)
  Sprint 2: PRD-002 + PRD-003 (bisa paralel setelah PRD-001 selesai)
  Sprint 3: PRD-004 + PRD-005 fitur moderasi (PRD-005 bisa parsial di Sprint 2)
```

---

## Dependency Type Legend

| Simbol | Tipe | Penjelasan |
|--------|------|------------|
| `──►` | HARD | PRD tujuan tidak bisa dimulai sebelum PRD sumber selesai |
| `◄────►` | SOFT | Bisa dikerjakan paralel, perlu sinkronisasi saat merge |
| `[paralel]` | INDEPENDENT | Bisa dikerjakan bersamaan tanpa konflik |

---

## Konflik & Catatan Koordinasi

| Konflik | PRD Terlibat | Resolusi |
|---------|-------------|----------|
| Navbar Fix (Sidebar + Auth Detection) | PRD-001 & PRD-005 | **Diselesaikan di PRD-001** — PRD-005 tidak menduplikasi scope ini |
| Admin Layout | PRD-001, 002, 003, 004, 005 | Semua menggunakan `admin-layout` yang dibuat di PRD-001 — PRD-001 harus selesai pertama |
| Spatie Activitylog | PRD-002, 003, 004, 005 | Library sudah terpasang; PRD-005 membuat halaman display-nya; PRD lain hanya menambahkan event yang di-log |

---

## Estimasi Total & Roadmap

| Sprint | PRD | Estimasi | Focus |
|--------|-----|----------|-------|
| Sprint 1 | PRD-001 + PRD-005 (pembersihan controller) | ~30 jam | Fondasi layout + cleanup codebase |
| Sprint 2 | PRD-002 + PRD-003 + PRD-005 (moderasi features) | ~48 jam | Fitur dinamis + moderasi |
| Sprint 3 | PRD-004 + finalisasi | ~23 jam | Keamanan data ekspor |
| **Total** | **5 PRD** | **~117 jam** | **~14.5 hari kerja** |

---

## Rekomendasi Prioritas Eksekusi

```
URUTAN EKSEKUSI (berdasarkan dependency + impact):

1. PRD-001 [Critical] — HARUS PERTAMA
   Alasan: Semua PRD lain bergantung pada layout yang dihasilkan PRD ini.
   Tanpa PRD-001, developer PRD-002 sampai 005 tidak punya template dasar.

2. PRD-005 Partial [High] — Bisa paralel dengan PRD-001
   Khusus task: Hapus controller redundan (independen, tidak butuh PRD-001)
   Sisa PRD-005: tunggu PRD-001 selesai

3. PRD-002 [High] — Setelah PRD-001 selesai
   Alasan: Life-critical information harus segera dinamis.
   Bisa dikerjakan paralel dengan PRD-003.

4. PRD-003 [High] — Setelah PRD-001 selesai
   Alasan: Eliminasi bottleneck deploy untuk update konten riset.
   Bisa dikerjakan paralel dengan PRD-002.

5. PRD-004 [High] — Setelah PRD-001 selesai, idealnya setelah PRD-005
   Alasan: Keamanan data adalah prerequisite sebelum riset skala penuh dimulai.
   Butuh setup queue & scheduler yang stabil.
```

---

## Catatan Penting

> ⚠️ **PRD-004 adalah PRD dengan Risk tertinggi** dari sisi keamanan data. Pastikan ada security review dari Kang Hendra sebelum implementasi dimulai.

> ⚠️ **PRD-002 adalah PRD dengan criticality tertinggi** dari sisi keselamatan user (remaja). Kontak konselor yang tidak akurat dapat membahayakan jiwa. Prioritaskan validasi konten awal dengan tim OPSI.

> ℹ️ **PRD-001 harus selesai 100%** sebelum developer lain mulai mengerjakan halaman frontend untuk PRD-002, 003, 004, dan 005.

> ℹ️ **Semua PRD berstatus Draft** dan memerlukan approval dari Mas Lutfi (Product Owner) sebelum eksekusi dimulai.

---

## Daftar Agent yang Terlibat

| Agent | Role | PRD yang Dikerjakan |
|-------|------|-------------------|
| Teh Ayu | Frontend Developer | PRD-001, 002, 003, 004, 005 (semua) |
| Kang Bayu | Backend Developer | PRD-001, 002, 003, 004, 005 (semua) |
| Kang Eka | Database Designer | PRD-002, 003, 004, 005 |
| Kang Farhan | QA / Tester | PRD-001, 002, 003, 004, 005 (semua) |
| Kang Hendra | Application Security | PRD-004 (mandatory review) |
| Teh Intan | Content Writer | Documentation untuk semua PRD |
| Sophia | PM Manager | Koordinasi, task tracking, approval facilitation |
| Kang Dadang | PRD Specialist | Pembuatan & pemeliharaan semua PRD |

---

*Index ini adalah dokumen hidup dan akan diperbarui setiap kali ada PRD baru atau perubahan status PRD existing.*
*Terakhir diperbarui: 2026-07-23 oleh Kang Dadang (PRD Specialist)*
