# 🎨 Laporan Evaluasi Visual & Pengalaman Pengguna (UI/UX)

Evaluasi ini dilakukan untuk memastikan keselarasan elemen visual, kenyamanan navigasi, dan konsistensi merek (*brand consistency*) di seluruh halaman **Aplikasi STEP**.

---

## 1. Temuan Ketidakselarasan Visual & Fungsional (UI/UX Gaps)

### 📌 Aspek Layout & Grid
*   **Bentrok Layout Utama (`blankLayout`):** 
    Berkas `blankLayout.blade.php` dimodifikasi secara paksa dengan menyisipkan baris kode `<nav>` (Navbar Publik) secara permanen. Hal ini merusak peruntukan dasar "Blank Layout" yang harusnya bersih dari navigasi global.
    *   *Dampaknya:* Halaman login & register cover terdorong ke bawah sebesar 60-70px, sehingga memotong visual penuh (100vh) dan memunculkan scrollbar yang tidak perlu.
*   **Ketiadaan Navigasi Sidebar Admin:** 
    Halaman dashboard admin dan dashboard peneliti diatur menggunakan layout `'front'` (layout publik). Hal ini mengakibatkan panel administrasi tidak memiliki sidebar navigasi (`verticalMenu.blade.php`). Admin harus mengetik URL secara manual untuk berpindah halaman moderasi ekspresi.

### 📌 Aspek Warna & Identitas Merek
*   **Peralihan Font Tiba-tiba:** 
    Halaman beranda publik memuat font kustom **Poppins**, sedangkan halaman Ruang Ekspresi dan Dashboard memuat font **Public Sans**. Transisi antar halaman publik ini memicu pergeseran visual (*flicker*) jenis huruf.
*   **Kehilangan Skema Warna STEP:** 
    Warna identitas STEP yang hangat (**Teal Deep** `#003d33` dan **Amber** `#f59e0b`) sangat menonjol di halaman beranda. Namun, saat masuk ke formulir Ruang Ekspresi (`ekspresi/create.blade.php`), skemanya kembali ke default template Vuexy (dominan biru/ungu).

### 📌 Aspek Navigasi & Alur Pengguna (User Flow)
*   **Navbar Melompat (Jumping Navbar):** 
    Posisi logo, tombol login, dan pengubah mode malam melompat-lompat saat beralih dari beranda utama ke ruang ekspresi karena perbedaan struktur navbar yang dipakai.
*   **Tombol Login Publik Statis:** 
    Navbar publik selalu menampilkan tombol "Login/Register" secara statis. Meskipun admin telah login, tombol tersebut tidak berubah menjadi menu profil dropdown (tidak ada pengkondisian `@auth` / `@guest`).
*   **Footer Default Template:** 
    Halaman Ruang Ekspresi menampilkan footer bawaan Vuexy yang memuat tautan unduhan tiruan, demo layout, dan form newsletter yang sama sekali tidak relevan dengan program riset kesehatan mental STEP.

---

## 🛠️ Rekomendasi Solusi untuk Tim Pengembang

### 1. Perbaikan Layout Publik STEP (`layouts/layoutStepPublic.blade.php`)
*   Kembalikan file `blankLayout.blade.php` ke fungsi dasarnya (murni `@yield('content')` tanpa navbar). Hal ini otomatis menormalkan halaman login/register cover.
*   Buat layout baru bernama `layoutStepPublic.blade.php` yang khusus mengimpor:
    *   Navbar publik yang dinamis mendeteksi status login (menampilkan nama & dropdown logout/dashboard jika sudah masuk `@auth`).
    *   Footer publik STEP yang disesuaikan secara khusus dengan materi riset OPSI 2026.
    *   Font **Poppins** yang dimuat secara global.

### 2. Harmonisasi Warna & Desain Tombol Ruang Ekspresi
*   Gunakan variabel CSS `:root` global untuk mendefinisikan warna brand STEP:
    ```css
    :root {
      --teal-deep: #003d33;
      --amber: #f59e0b;
      --cream: #fdf6ee;
    }
    ```
*   Terapkan warna `--teal-deep` dan `--amber` pada elemen tombol utama (*primary buttons*), *card header*, dan badge kategori di halaman input ekspresi.

### 3. Migrasi Dashboard Admin ke Layout Sidebar Fungsional
*   Hapus baris `$pageConfigs = ['layout' => 'front'];` pada Controller atau View admin agar sistem otomatis memuat layout `contentNavbarLayout`.
*   Daftarkan rute navigasi dashboard admin dan moderasi ekspresi pada berkas konfigurasi menu sidebar template (`verticalMenu.json`).
