# BukuHub - Sistem Perpustakaan QR Code

[![Continuous Integration](https://github.com/ikhsan3adi/sistem-perpustakaan-qr-code/actions/workflows/ci.yml/badge.svg)](https://github.com/ikhsan3adi/sistem-perpustakaan-qr-code/actions/workflows/ci.yml)

![Preview](https://github.com/ikhsan3adi/sistem-perpustakaan-qr-code/raw/main/screenshots/home.png)

> [!NOTE]
>
> ## Fitur
>
> - Login, Register & Magic login link (via Email)
> - Dashboard admin
> - QR Code anggota
> - QR Code peminjaman
> - Sistem denda
> - Dan lainnya.
>
> ## Framework dan Library Yang Digunakan
>
> - [CodeIgniter 4](https://codeigniter.com/)
> - [CodeIgniter Shield](https://codeigniter4.github.io/shield/)
> - [Bootstrap 5](https://getbootstrap.com/)
> - [Modernize Free Bootstrap 5 Admin Template](https://adminmart.com/product/modernize-free-bootstrap-5-admin-template/)
> - [Tabler Icons](https://tabler-icons.io/)
> - [Apex Charts](https://apexcharts.com/)
> - [Endroid QR Code Generator](https://github.com/endroid/qr-code)
> - [Mebjas Html5-QRCode Scanner](https://github.com/mebjas/html5-qrcode)

## Cara Penggunaan

### Persyaratan

- [Composer](https://getcomposer.org/).
- PHP 8.1+ dan MySQL atau [XAMPP](https://www.apachefriends.org/download.html) versi 8.1+ dengan mengaktifkan extension `-intl` dan `-gd`.
- (Opsional) Kamera/webcam untuk menjalankan qr scanner. Bisa juga menggunakan kamera HP dengan bantuan software DroidCam.

### Instalasi

- Unduh dan impor kode proyek ini ke dalam direktori proyek anda (htdocs).
- Penting ⚠️. Jika belum memiliki file `.env`, salin/rename file `.env.example` menjadi `.env`
- (Opsional) Konfigurasi file `.env` untuk mengatur parameter seperti koneksi database dan pengaturan lainnya sesuai dengan lingkungan pengembangan Anda.
- Penting ⚠️. Install dependencies yang diperlukan dengan cara menjalankan perintah berikut di terminal:

```shell
composer install
```

- Buat database `db_book_library` di phpMyAdmin / mysql
- Penting ⚠️. Jalankan migrasi database untuk membuat struktur tabel yang diperlukan. Ketikkan perintah berikut di terminal:

```shell
php spark migrate --all
```

- Penting ⚠️. Karena belum memiliki akun admin, untuk mengakses halaman admin, anda memerlukan user/akun dengan level `superadmin`. Jalankan perintah berikut untuk membuat akun `superadmin`:

```shell
php spark db:seed SuperAdminSeeder
```

> [!TIP]
>
> - (Opsional) Isi database dengan data dummy / seeder.
>
> ```shell
> php spark db:seed Seeder # semua seeder
> php spark db:seed BookSeeder # buku
> php spark db:seed MemberSeeder # anggota
> php spark db:seed LoanSeeder # peminjaman, pengembalian & denda
> ```

- Jalankan website

```shell
php spark serve
```

- Buka [http://localhost:8080](http://localhost:8080)
- Login dengan kredensial `superadmin` berikut:

```txt
username : superadmin
email    : superadmin@admin.com
password : superadmin
```

## Contributing

Kami menerima kontribusi dari komunitas terbuka untuk meningkatkan aplikasi ini. Jika Anda menemukan masalah, bug, atau memiliki saran untuk peningkatan, silakan buat issue baru dalam repositori ini atau ajukan pull request.

## Donasi

[![Donate paypal](https://img.shields.io/badge/Donate-PayPal-green.svg?style=for-the-badge)](https://paypal.me/xannxett?country.x=ID&locale.x=en_US)
[![Donate saweria](https://img.shields.io/badge/Donate-Saweria-red?style=for-the-badge&link=https%3A%2F%2Fsaweria.co%2Fxiboxann)](https://saweria.co/xiboxann)

## Lisensi

[![GitHub license](https://img.shields.io/github/license/ikhsan3adi/sistem-perpustakaan-qr-code?style=for-the-badge)](https://github.com/ikhsan3adi/sistem-perpustakaan-qr-code/raw/main/LICENSE)

## Authors

- [@ikhsan3adi](https://www.github.com/ikhsan3adi)
