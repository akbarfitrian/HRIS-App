<?php

// Tambahkan baris ini ke bagian atas routes/web.php (bareng use statement lain)
// use App\Http\Controllers\SalaryComponentController;
// use App\Http\Controllers\PayrollController;

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
