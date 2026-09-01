<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use App\Services\PayrollService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->query('period', now()->format('Y-m'));

        $payrolls = Payroll::with('employee.user')
            ->where('period', $period)
            ->orderBy('employee_id')
            ->paginate(15)
            ->withQueryString();

        return view('payroll.index', compact('payrolls', 'period'));
    }

    public function generate(Request $request, PayrollService $payrollService)
    {
        $request->validate([
            'period' => ['required', 'regex:/^\d{4}-\d{2}$/'],
        ]);

        $period = $request->input('period');
        $employees = Employee::where('status', 'active')->get();

        $generated = 0;
        $skipped = 0;

        foreach ($employees as $employee) {
            $result = $payrollService->generateForEmployee($employee, $period);
            $result ? $generated++ : $skipped++;
        }

        return redirect()->route('payroll.index', ['period' => $period])
            ->with('success', "Payroll periode {$period} selesai digenerate: {$generated} berhasil, {$skipped} dilewati (belum ada komponen gaji).");
    }

    public function show(Payroll $payroll)
    {
        $this->authorizeView($payroll);

        return view('payroll.slip', compact('payroll'));
    }

    public function downloadSlip(Payroll $payroll)
    {
        $this->authorizeView($payroll);

        $pdf = Pdf::loadView('payroll.slip', compact('payroll'));

        $filename = "slip-gaji-{$payroll->employee->employee_code}-{$payroll->period}.pdf";

        return $pdf->download($filename);
    }

    public function history()
    {
        $employee = Auth::user()->employee;

        $payrolls = $employee
            ? $employee->payrolls()->orderByDesc('period')->paginate(12)
            : collect();

        return view('payroll.history', compact('payrolls', 'employee'));
    }

    private function authorizeView(Payroll $payroll): void
    {
        $user = Auth::user();

        if ($user->hasRole('Admin')) {
            return;
        }

        if ($user->employee && $user->employee->id === $payroll->employee_id) {
            return;
        }

        abort(403, 'Kamu tidak punya akses ke slip gaji ini.');
    }
}
