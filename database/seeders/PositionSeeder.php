<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            'Human Resources' => ['HR Manager', 'HR Staff'],
            'Finance' => ['Finance Manager', 'Accountant'],
            'Engineering' => ['Software Engineer', 'QA Engineer'],
            'Marketing' => ['Marketing Manager', 'Content Specialist'],
        ];

        foreach ($positions as $departmentName => $names) {
            $department = Department::where('name', $departmentName)->first();

            foreach ($names as $name) {
                Position::firstOrCreate([
                    'name' => $name,
                    'department_id' => $department->id,
                ]);
            }
        }
    }
}
