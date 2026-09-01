<?php

// Tambahkan baris ini ke bagian atas routes/web.php (bersama use statement lain)
// use App\Http\Controllers\EmployeeController;

// Tambahkan block ini di routes/web.php, biasanya setelah route dashboard bawaan Breeze
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::resource('employees', EmployeeController::class);
});

/*
|--------------------------------------------------------------------------
| Catatan penting soal middleware 'role'
|--------------------------------------------------------------------------
| Middleware 'role' berasal dari package spatie/laravel-permission dan
| perlu didaftarkan aliasnya dulu sebelum bisa dipakai di route.
|
| Laravel 11 ke atas -> daftarkan di bootstrap/app.php:
|
|   ->withMiddleware(function (Middleware $middleware) {
|       $middleware->alias([
|           'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
|       ]);
|   })
|
| Laravel 10 ke bawah -> daftarkan di app/Http/Kernel.php,
| di dalam properti $middlewareAliases:
|
|   protected $middlewareAliases = [
|       // ...alias bawaan lainnya
|       'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
|   ];
*/
