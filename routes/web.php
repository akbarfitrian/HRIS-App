<?php

use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SalaryComponentController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::resource('employees', EmployeeController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check-in');
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.check-out');
});

Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/attendance/recap', [AttendanceController::class, 'recap'])->name('attendance.recap');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Pengajuan cuti — semua user yang login & terhubung ke data employee
Route::middleware('auth')->group(function () {
    Route::get('/leave', [LeaveRequestController::class, 'index'])->name('leave.index');
    Route::get('/leave/create', [LeaveRequestController::class, 'create'])->name('leave.create');
    Route::post('/leave', [LeaveRequestController::class, 'store'])->name('leave.store');
    Route::delete('/leave/{leaveRequest}', [LeaveRequestController::class, 'cancel'])->name('leave.cancel');
});

// Approval cuti — khusus Manager
// (kalau nanti mau dibuka juga buat Admin, ganti jadi role:Admin|Manager)
Route::middleware(['auth', 'role:Admin|Manager'])->group(function () {
    Route::get('/leave/approvals', [LeaveRequestController::class, 'approvals'])->name('leave.approvals');
    Route::post('/leave/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('leave.approve');
    Route::post('/leave/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('leave.reject');
});

// Komponen gaji & generate payroll — khusus Admin
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/salary', [SalaryComponentController::class, 'index'])->name('salary.index');
    Route::get('/salary/{employee}/edit', [SalaryComponentController::class, 'edit'])->name('salary.edit');
    Route::put('/salary/{employee}', [SalaryComponentController::class, 'update'])->name('salary.update');

    Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::post('/payroll/generate', [PayrollController::class, 'generate'])->name('payroll.generate');
});

// Riwayat & slip gaji — semua user login, akses per-payroll dicek di controller
// (Admin bisa lihat semua, Employee cuma bisa lihat slip miliknya sendiri)
Route::middleware('auth')->group(function () {
    Route::get('/payroll/history', [PayrollController::class, 'history'])->name('payroll.history');
    Route::get('/payroll/{payroll}', [PayrollController::class, 'show'])->name('payroll.show');
    Route::get('/payroll/{payroll}/download', [PayrollController::class, 'downloadSlip'])->name('payroll.download');
});

require __DIR__.'/auth.php';
