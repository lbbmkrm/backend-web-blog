# **API Blog Laravel**

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>
<p align="center">
    Backend untuk aplikasi blog modern yang dibangun dengan Laravel, dirancang dengan penekanan kuat pada <strong>Clean Code</strong>, <strong>maintainability</strong>, dan penerapan <strong>Design Patterns</strong> yang solid.
</p>

## Filosofi Desain

Proyek ini bukan sekadar API fungsional, tetapi juga sebuah contoh bagaimana membangun aplikasi Laravel yang terukur dan mudah dikelola. Filosofi utamanya adalah:

-   **Pemisahan Tanggung Jawab (Separation of Concerns)**: Setiap kelas memiliki satu tanggung jawab yang jelas, membuat kode lebih mudah dipahami dan diuji.
-   **Kode yang Ekspresif**: Menulis kode yang tidak hanya berfungsi, tetapi juga mudah dibaca dan dipahami oleh developer lain.
-   **Testability**: Arsitektur dirancang agar setiap komponen dapat diuji secara terisolasi.

---

## Arsitektur & Design Pattern

Aplikasi ini mengadopsi arsitektur berlapis yang terinspirasi dari _Clean Architecture_. Ini memisahkan logika aplikasi menjadi beberapa lapisan independen.

#### **1. Controller Layer (`app/Http/Controllers`)**

-   **Tanggung Jawab**: Hanya sebagai "pintu gerbang" untuk permintaan HTTP. Tugasnya adalah menerima _request_, memvalidasinya, dan meneruskannya ke _Service Layer_.
-   **Prinsip**: Controller dibuat "tipis" (_thin controller_). Mereka tidak mengandung logika bisnis apa pun.

#### **2. Service Layer (`app/Services`)**

-   **Tanggung Jawab**: Inilah inti dari logika bisnis aplikasi. Semua aturan, proses, dan orkestrasi antar komponen terjadi di sini.
-   **Pattern**: Mengimplementasikan _Service Layer Pattern_. Contohnya, `BlogService` akan menangani semua logika terkait pembuatan, pembaruan, dan pengelolaan blog.

#### **3. Repository Layer (`app/Repositories`)**

-   **Tanggung Jawab**: Sebagai jembatan antara _Service Layer_ dan _database_. Lapisan ini bertanggung jawab untuk semua kueri database.
-   **Pattern**: Mengimplementasikan _Repository Pattern_. Ini mengabstraksi sumber data, sehingga _Service Layer_ tidak perlu tahu apakah data berasal dari Eloquent, Query Builder, atau bahkan sumber eksternal. Ini juga membuat _mocking_ saat testing menjadi sangat mudah.

#### **4. Model Layer (`app/Models`)**

-   **Tanggung Jawab**: Merepresentasikan entitas data dan hubungan antar tabel menggunakan Laravel Eloquent ORM.

#### **5. Resource Layer (`app/Http/Resources`)**

-   **Tanggung Jawab**: Mengubah data dari _Model_ menjadi format JSON yang konsisten dan terstruktur untuk respons API. Ini memastikan bahwa struktur output API tetap terkontrol dan tidak membocorkan detail database.

Diagram Alur Permintaan:
`Request` -> `Controller` -> `Service` -> `Repository` -> `Model/Database`

---

## Fitur Unggulan

-   **Manajemen Pengguna & Autentikasi**: Registrasi, login, dan profil pengguna dengan Laravel Sanctum.
-   **Interaksi Sosial**: Sistem Follow/Unfollow, Komentar, dan Suka.
-   **Manajemen Konten**: CRUD penuh untuk Blog, Kategori, dan Tag.
-   **Fitur Tambahan**: Bookmark dan Sistem Notifikasi _real-time_.

## Panduan Instalasi

### 1. Prasyarat

-   PHP (versi sesuai `composer.json`)
-   Composer
-   Database (misalnya, MySQL)

### 2. Instalasi

1.  **Kloning repositori:**

    ```bash
    git clone [URL_REPOSITORY_ANDA]
    cd [NAMA_DIREKTORI]
    ```

2.  **Instal dependensi PHP:**

    ```bash
    composer install
    ```

3.  **Konfigurasi Lingkungan:**

    -   Salin file `.env.example` menjadi `.env`.
        ```bash
        cp .env.example .env
        ```
    -   Buat kunci aplikasi baru.
        ```bash
        php artisan key:generate
        ```
    -   Atur koneksi database Anda di dalam file `.env` (DB_DATABASE, DB_USERNAME, DB_PASSWORD, dll.).

4.  **Migrasi & Seeding Database:**

    -   Jalankan migrasi untuk membuat semua tabel.
        ```bash
        php artisan migrate
        ```
    -   (Opsional) Isi database dengan data sampel untuk pengujian.
        ```bash
        php artisan db:seed
        ```

5.  **Jalankan Server:**
    ```bash
    php artisan serve
    ```
    API sekarang akan berjalan di `http://127.0.0.1:8000`.

## Dokumentasi API

Endpoint API didefinisikan dalam file `routes/api.php`. Anda dapat menggunakan alat seperti [Postman](https://www.postman.com/) atau [Insomnia](https://insomnia.rest/) untuk menguji endpoint.

Beberapa contoh endpoint:

-   `POST /api/register` - Mendaftarkan pengguna baru.
-   `POST /api/login` - Login untuk mendapatkan token otentikasi.
-   `GET /api/blogs` - Mendapatkan daftar semua postingan blog.
-   `POST /api/blogs` - (Memerlukan Autentikasi) Membuat postingan baru.
-   `GET /api/users/{id}` - Melihat profil pengguna.
