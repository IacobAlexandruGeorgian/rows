<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['Developer', 'Designer', 'Manager', 'QA'];

        $employees = Employee::all();

        Project::all()->each(function ($project) use ($employees, $roles) {

            $randomEmployees = $employees->random(3);

            foreach ($randomEmployees as $employee) {

                $project->employees()->attach($employee->id, [
                    'role' => $roles[array_rand($roles)],
                ]);
                
            }
        });
    }
}
