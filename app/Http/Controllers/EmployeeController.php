<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['user', 'department', 'position'])
            ->latest()
            ->paginate(10);

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $positions = Position::orderBy('name')->get();

        return view('employees.create', compact('departments', 'positions'));
    }

    public function store(StoreEmployeeRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
            $user->assignRole('Employee');

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('employees', 'public');
            }

            Employee::create([
                'user_id' => $user->id,
                'employee_code' => $validated['employee_code'],
                'department_id' => $validated['department_id'],
                'position_id' => $validated['position_id'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'hire_date' => $validated['hire_date'],
                'photo' => $photoPath,
                'status' => $validated['status'],
            ]);
        });

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function show(Employee $employee)
    {
        $employee->load(['user', 'department', 'position']);

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $employee->load('user');
        $departments = Department::orderBy('name')->get();
        $positions = Position::orderBy('name')->get();

        return view('employees.edit', compact('employee', 'departments', 'positions'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request, $employee) {
            $employee->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            if (!empty($validated['password'])) {
                $employee->user->update(['password' => Hash::make($validated['password'])]);
            }

            $photoPath = $employee->photo;
            if ($request->hasFile('photo')) {
                if ($employee->photo) {
                    Storage::disk('public')->delete($employee->photo);
                }
                $photoPath = $request->file('photo')->store('employees', 'public');
            }

            $employee->update([
                'employee_code' => $validated['employee_code'],
                'department_id' => $validated['department_id'],
                'position_id' => $validated['position_id'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'hire_date' => $validated['hire_date'],
                'photo' => $photoPath,
                'status' => $validated['status'],
            ]);
        });

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        // Menghapus user otomatis menghapus employee juga
        // karena foreign key user_id pakai cascadeOnDelete()
        $employee->user()->delete();

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}
