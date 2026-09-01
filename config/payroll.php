<?php

return [
    // Potongan per 1 hari status 'late' di attendance, dalam Rupiah.
    // Bisa dioverride lewat .env: PAYROLL_LATE_DEDUCTION=25000
    'late_deduction_per_day' => env('PAYROLL_LATE_DEDUCTION', 50000),
];
