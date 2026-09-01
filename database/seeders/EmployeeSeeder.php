<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        // Akun admin default untuk login pertama kali
        $admin = User::firstOrCreate(
            ['email' => 'admin@hris.test'],
            ['name' => 'Admin HRIS', 'password' => Hash::make('password')]
        );
        $admin->assignRole('Admin');

        // Contoh satu karyawan
        $department = Department::where('name', 'Engineering')->first();
        $position = Position::where('name', 'Software Engineer')->first();

        $user = User::firstOrCreate(
            ['email' => 'karyawan@hris.test'],
            ['name' => 'Budi Santoso', 'password' => Hash::make('password')]
        );
        $user->assignRole('Employee');

        Employee::firstOrCreate(
            ['user_id' => $user->id],
            [
                'employee_code' => 'EMP-0001',
                'department_id' => $department->id,
                'position_id' => $position->id,
                'phone' => '081234567890',
                'address' => 'Semarang, Jawa Tengah',
                'hire_date' => now()->subYear(),
                'status' => 'active',
            ]
        );
    }
}
