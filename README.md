## 📋 Prasyarat
Sebelum memulai, pastikan Anda sudah menginstall:
- [PHP](https://www.php.net/downloads) (versi >= 8.1)
- [Composer](https://getcomposer.org/download/)
- [Node.js & NPM](https://nodejs.org/) (opsional, jika menggunakan frontend build seperti Vite)
- [MySQL](https://dev.mysql.com/downloads/) atau [PostgreSQL](https://www.postgresql.org/download/) (sesuai kebutuhan project)
- [Git](https://git-scm.com/)

---

## 🔧 Instalasi

1. **Clone repository**
   ```
   git clone https://github.com/username/nama-project.git
   ```
   cd nama-project
Install dependencies PHP via Composer

bash
Copy
Edit
composer install
Install dependencies Javascript (jika ada frontend build)

bash
Copy
Edit
npm install
Copy file environment

bash
Copy
Edit
cp .env.example .env
Lalu sesuaikan konfigurasi database, mail, dll. di dalam file .env.

Generate application key

bash
Copy
Edit
php artisan key:generate
Jalankan migrasi database

bash
Copy
Edit
php artisan migrate
(Opsional) Jalankan seeder untuk data awal

bash
Copy
Edit
php artisan db:seed
Jalankan aplikasi

bash
Copy
Edit
php artisan serve
Akses di browser: http://localhost:8000
