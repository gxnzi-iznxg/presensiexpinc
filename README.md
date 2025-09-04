
## 🔧 Instalasi

1. **Clone repository**
   ```
   git clone https://github.com/gxnzi-iznxg/presensi-exp.inc.git
   cd presensiexpinc
   ```
2. **Install dependencies PHP via Composer**
   ```
   composer install
   ```
3. **Install dependencies Javascript**
   ```
   npm install
   ```
4. **Copy file environment**
   ```
   cp .env.example .env
   ```
   Lalu sesuaikan konfigurasi database, mail, dll. di dalam file .env.

5. **Generate application key**
   ```
   php artisan key:generate
   ```
6. **Jalankan migrasi database**
   ```
   php artisan migrate
   ```
7. **Jalankan seeder untuk data awal**
   ```
   php artisan db:seed
   ```
8. **Jalankan aplikasi**
   ```
   php artisan serve
   ```
