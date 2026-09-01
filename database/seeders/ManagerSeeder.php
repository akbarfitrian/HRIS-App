<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ManagerSeeder extends Seeder
{
    public function run(): void
    {
        $department = Department::where('name', 'Human Resources')->first();
        $position = Position::where('name', 'HR Manager')->first();

        $user = User::firstOrCreate(
            ['email' => 'manager@hris.test'],
            ['name' => 'Siti Rahma', 'password' => Hash::make('password')]
        );
        $user->assignRole('Manager');

        Employee::firstOrCreate(
            ['user_id' => $user->id],
            [
                'employee_code' => 'EMP-0002',
                'department_id' => $department->id,
                'position_id' => $position->id,
                'phone' => '081234567891',
                'address' => 'Semarang, Jawa Tengah',
                'hire_date' => now()->subYears(2),
                'status' => 'active',
            ]
        );
    }
}
