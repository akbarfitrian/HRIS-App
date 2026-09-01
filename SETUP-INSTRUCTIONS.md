# Setup Fase 1 — Auth & Employee CRUD

## 1. Buat project baru
```bash
composer create-project laravel/laravel hris-app
cd hris-app
```

## 2. Install Breeze (auth scaffolding)
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build
```

## 3. Install Spatie Permission
```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

## 4. Setup `.env`
Sesuaikan `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, lalu buat database-nya (MySQL/PostgreSQL/SQLite, bebas).

## 5. Salin file-file dari folder ini ke project
| Dari folder ini | Ke dalam project |
|---|---|
| `database/migrations/*.php` | `database/migrations/` |
| `database/seeders/*.php` | `database/seeders/` (replace `DatabaseSeeder.php`) |
| `app/Models/*.php` | `app/Models/` (replace `User.php`) |
| `app/Http/Controllers/EmployeeController.php` | `app/Http/Controllers/` |
| `app/Http/Requests/*.php` | `app/Http/Requests/` |
| `resources/views/employees/` | `resources/views/employees/` |
| Isi `routes/routes-tambahan.php` | Tempel ke dalam `routes/web.php` |

## 6. Daftarkan middleware `role`
Package Spatie butuh alias middleware didaftarkan dulu sebelum dipakai di route.

**Laravel 11 ke atas** — edit `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
    ]);
})
```

**Laravel 10 ke bawah** — edit `app/Http/Kernel.php`, tambahkan ke `$middlewareAliases`:
```php
'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
```

## 7. Migrasi & seed database
```bash
php artisan migrate
php artisan db:seed
```

## 8. Buat symlink storage (supaya upload foto bisa diakses publik)
```bash
php artisan storage:link
```

## 9. Jalankan server
```bash
php artisan serve
```

## Akun untuk testing
| Role | Email | Password |
|---|---|---|
| Admin | admin@hris.test | password |
| Employee | karyawan@hris.test | password |

Login sebagai Admin, lalu buka `/employees` untuk coba CRUD-nya. Route ini sengaja dikunci hanya untuk role Admin — kalau login sebagai Employee dan akses `/employees`, harusnya kena 403.

## Kemungkinan error umum
- **"Class RoleMiddleware not found"** → pastikan langkah 6 sudah bener dan cache config di-clear: `php artisan config:clear`.
- **Foto tidak muncul** → pastikan sudah jalanin `php artisan storage:link` (langkah 8).
- **"Call to a member function assignRole() on null"** → pastikan `RoleSeeder` jalan duluan sebelum `EmployeeSeeder` (urutannya sudah benar di `DatabaseSeeder.php`).
