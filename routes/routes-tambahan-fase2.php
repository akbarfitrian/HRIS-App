<?php

// Tambahkan baris ini ke bagian atas routes/web.php (bareng use statement lain)
// use App\Http\Controllers\AttendanceController;

// Presensi diri sendiri — semua user yang login (Admin/Manager/Employee)
Route::middleware('auth')->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check-in');
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.check-out');
});

// Rekap kehadiran semua karyawan — khusus Admin
// (kalau nanti mau dibuka juga buat Manager, ganti jadi role:Admin|Manager)
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/attendance/recap', [AttendanceController::class, 'recap'])->name('attendance.recap');
});
