<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\SalaryComponent;
use Carbon\Carbon;

class PayrollService
{
    /**
     * Generate (atau update) payroll 1 employee untuk 1 periode.
     * Return null kalau employee belum punya komponen gaji sama sekali
     * (gak boleh asal generate payroll 0 rupiah).
     */
    public function generateForEmployee(Employee $employee, string $period): ?Payroll
    {
        $periodStart = Carbon::createFromFormat('Y-m', $period)->startOfMonth();
        $periodEnd = $periodStart->copy()->endOfMonth();

        // Ambil komponen gaji yang berlaku paling akhir sebelum/pada akhir periode ini
        $salaryComponent = SalaryComponent::where('employee_id', $employee->id)
            ->where('effective_date', '<=', $periodEnd)
            ->orderByDesc('effective_date')
            ->first();

        if (! $salaryComponent) {
            return null;
        }

        $lateCount = $employee->attendances()
            ->whereBetween('date', [$periodStart->toDateString(), $periodEnd->toDateString()])
            ->where('status', 'late')
            ->count();

        $lateDeductionPerDay = (float) config('payroll.late_deduction_per_day', 50000);
        $lateDeduction = $lateCount * $lateDeductionPerDay;

        $netSalary = $salaryComponent->basic_salary
            + $salaryComponent->allowance
            - $salaryComponent->deduction
            - $lateDeduction;

        return Payroll::updateOrCreate(
            [
                'employee_id' => $employee->id,
                'period' => $period,
            ],
            [
                'basic_salary' => $salaryComponent->basic_salary,
                'total_allowance' => $salaryComponent->allowance,
                'total_deduction' => $salaryComponent->deduction,
                'late_deduction' => $lateDeduction,
                'net_salary' => max($netSalary, 0),
                'generated_at' => now(),
            ]
        );
    }
}
