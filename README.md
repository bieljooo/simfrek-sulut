# SIMFREK SULUT

Sistem Informasi Monitoring Spektrum Frekuensi Sulawesi Utara berbasis Laravel 11.

## Tech Stack

![Laravel](https://img.shields.io/badge/Laravel_11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Blade](https://img.shields.io/badge/Blade_UI-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Filament](https://img.shields.io/badge/Filament_Admin-F59E0B?style=for-the-badge)
![PHP](https://img.shields.io/badge/PHP_8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-323330?style=for-the-badge&logo=javascript&logoColor=F7DF1E)
![Bootstrap](https://img.shields.io/badge/Bootstrap_5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white)
![Leaflet](https://img.shields.io/badge/Leaflet_Map-199900?style=for-the-badge)
![SweetAlert2](https://img.shields.io/badge/SweetAlert2-4A90E2?style=for-the-badge)
![Tabler Icons](https://img.shields.io/badge/Tabler_Icons-1F6FEB?style=for-the-badge)

## Fitur Utama

- Peta spektrum frekuensi radio Sulawesi dengan filter wilayah.
- Dashboard admin berbasis Filament.
- Import dan export data berkala.
- Pengaturan style website.
- Halaman kontak dan login admin.

## Stack

- Backend: Laravel 11, PHP 8.2+
- Templating: Blade
- Admin Panel: Filament
- Database: MySQL
- Frontend: Blade, JavaScript, Bootstrap 5
- Maps: Leaflet
- Build Tool: Vite
- Alert: SweetAlert2
- Icon: Tabler Icons

## Setup

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --force
```

```bash
npm install
npm run dev
```

## Sebelum Push

- Jangan commit `.env`.
- Jangan commit `vendor`, `node_modules`, `public/build`, atau cache Laravel.
- Jangan commit dump database seperti `.sql` atau `.sqlite`.

## GitHub Language

File `*.blade.php` dikonfigurasi agar terbaca sebagai `Blade` oleh GitHub Linguist melalui `.gitattributes`.

## Push ke GitHub

```bash
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/USERNAME/NAMA-REPO.git
git push -u origin main
```
