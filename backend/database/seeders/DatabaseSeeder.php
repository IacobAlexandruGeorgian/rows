<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // task 2
        $this->call([
            EmployeeSeeder::class,
            ProjectSeeder::class,
            RoleSeeder::class
        ]);
    }
}
