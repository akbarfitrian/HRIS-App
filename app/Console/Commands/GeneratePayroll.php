<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Services\PayrollService;
use Illuminate\Console\Command;

class GeneratePayroll extends Command
{
    protected $signature = 'payroll:generate {period? : Periode format YYYY-MM, default bulan berjalan}';

    protected $description = 'Generate payroll bulanan untuk semua employee aktif';

    public function handle(PayrollService $payrollService): int
    {
        $period = $this->argument('period') ?? now()->format('Y-m');

        if (! preg_match('/^\d{4}-\d{2}$/', $period)) {
            $this->error('Format periode salah. Gunakan format YYYY-MM, contoh: 2026-09');

            return self::FAILURE;
        }

        $employees = Employee::where('status', 'active')->get();

        if ($employees->isEmpty()) {
            $this->warn('Tidak ada employee aktif.');

            return self::SUCCESS;
        }

        $generated = 0;
        $skipped = 0;

        foreach ($employees as $employee) {
            $result = $payrollService->generateForEmployee($employee, $period);

            if ($result === null) {
                $this->warn("Skip {$employee->employee_code}: belum ada data komponen gaji.");
                $skipped++;

                continue;
            }

            $this->info("Payroll {$employee->employee_code} periode {$period}: Rp ".number_format((float) $result->net_salary, 0, ',', '.'));
            $generated++;
        }

        $this->newLine();
        $this->info("Selesai. {$generated} payroll dibuat/diupdate, {$skipped} dilewati.");

        return self::SUCCESS;
    }
}
