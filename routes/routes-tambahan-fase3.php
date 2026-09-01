<?php

// Tambahkan baris ini ke bagian atas routes/web.php (bareng use statement lain)
// use App\Http\Controllers\LeaveRequestController;

// Pengajuan cuti — semua user yang login & terhubung ke data employee
Route::middleware('auth')->group(function () {
    Route::get('/leave', [LeaveRequestController::class, 'index'])->name('leave.index');
    Route::get('/leave/create', [LeaveRequestController::class, 'create'])->name('leave.create');
    Route::post('/leave', [LeaveRequestController::class, 'store'])->name('leave.store');
    Route::delete('/leave/{leaveRequest}', [LeaveRequestController::class, 'cancel'])->name('leave.cancel');
});

// Approval cuti — khusus Manager
// (kalau nanti mau dibuka juga buat Admin, ganti jadi role:Admin|Manager)
Route::middleware(['auth', 'role:Manager'])->group(function () {
    Route::get('/leave/approvals', [LeaveRequestController::class, 'approvals'])->name('leave.approvals');
    Route::post('/leave/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('leave.approve');
    Route::post('/leave/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('leave.reject');
});
