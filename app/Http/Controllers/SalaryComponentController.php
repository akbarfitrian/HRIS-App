<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSalaryComponentRequest;
use App\Models\Employee;

class SalaryComponentController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['user', 'latestSalaryComponent'])
            ->orderBy('employee_code')
            ->paginate(15);

        return view('salary.index', compact('employees'));
    }

    public function edit(Employee $employee)
    {
        $current = $employee->salaryComponents()->latest('effective_date')->first();

        return view('salary.edit', compact('employee', 'current'));
    }

    public function update(StoreSalaryComponentRequest $request, Employee $employee)
    {
        $employee->salaryComponents()->create($request->validated());

        return redirect()->route('salary.index')
            ->with('success', "Komponen gaji {$employee->employee_code} berhasil disimpan.");
    }
}
