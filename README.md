# HRIS Sederhana — Laravel

Sistem HRIS (Human Resource Information System) skala menengah, dibangun sebagai proyek portofolio. Fokus utamanya ke logic bisnis nyata — relasi data, role-based access, dan kalkulasi — tanpa fitur enterprise yang berlebihan (belum ada payroll pajak, approval berjenjang, atau integrasi biometric).

## Fitur

**Employee Management**
- CRUD data karyawan dengan relasi departemen & posisi
- Role: Admin/HR, Manager, Employee (via `spatie/laravel-permission`)

**Attendance**
- Check-in / check-out harian dengan validasi anti-double-checkin
- Rekap kehadiran per bulan
- Deteksi status telat otomatis berdasarkan jam kerja standar

**Leave Management**
- Pengajuan cuti dengan validasi sisa kuota & bentrok tanggal
- Approval/rejection oleh Manager (atau Admin)
- Notifikasi email otomatis saat status cuti berubah (queue-based)

**Payroll Sederhana**
- Komponen gaji per karyawan (gaji pokok, tunjangan, potongan), tersimpan sebagai histori
- Generate payroll bulanan otomatis, termasuk potongan keterlambatan berdasarkan data attendance
- Slip gaji dalam format PDF, bisa diunduh oleh Admin maupun karyawan yang bersangkutan

**Dashboard**
- Dashboard Admin: total karyawan, jumlah cuti hari ini, jumlah keterlambatan bulan ini
- Dashboard Employee: data diri, sisa kuota cuti per jenis, slip gaji terakhir

## Tech Stack

- **Framework:** Laravel
- **Auth:** Laravel Breeze
- **Role & Permission:** `spatie/laravel-permission`
- **PDF slip gaji:** `barryvdh/laravel-dompdf`
- **Queue:** database driver (notifikasi email cuti)
- **Frontend:** Blade + Alpine.js

## Instalasi

```bash
git clone <url-repo-ini>
cd hris-app

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Atur koneksi database di `.env`, lalu:

```bash
php artisan migrate
php artisan db:seed
```

Jalankan queue worker di terminal terpisah (dibutuhkan untuk notifikasi email cuti):

```bash
php artisan queue:work
```

Build asset & jalankan server:

```bash
npm run build
php artisan serve
```

Buka `http://127.0.0.1:8000`.

## Akun Demo

| Role     | Email                  | Password   |
|----------|-------------------------|------------|
| Admin/HR | admin@hris.test         | password   |
| Manager  | manager@hris.test       | password   |
| Employee | karyawan@hris.test      | password   |

## Struktur Database (ringkas)

- `departments`, `positions` — struktur organisasi
- `employees` — data karyawan, terhubung ke `users`
- `attendances` — kehadiran harian per karyawan
- `leave_types`, `leave_requests` — jenis cuti & pengajuannya
- `salary_components`, `payrolls` — komponen gaji (histori) & hasil payroll per periode

## Alur Pengujian Cepat

1. Login sebagai **Admin** → set komponen gaji karyawan di `/salary` → generate payroll di `/payroll`.
2. Login sebagai **Employee** → ajukan cuti di `/leave` → cek dashboard untuk sisa kuota & slip gaji terakhir.
3. Login sebagai **Manager** (atau Admin) → approve/reject pengajuan cuti di `/leave/approvals` → cek `storage/logs/laravel.log` untuk notifikasi email.

## Catatan

Proyek ini dikembangkan bertahap per fase (setup & auth, attendance, leave management, payroll, dashboard) sebagai latihan membangun sistem dengan relasi data dan logic bisnis yang realistis. Beberapa hal yang sengaja belum termasuk dalam scope: payroll pajak, approval berjenjang, dan integrasi biometric.