<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('division_unit')->insert([
            ['division_unit' => 'Null', 'created_at' => now(), 'updated_at' => now()],
            ['division_unit' => 'Finance', 'created_at' => now(), 'updated_at' => now()],
            ['division_unit' => 'Human Resources', 'created_at' => now(), 'updated_at' => now()],
            ['division_unit' => 'Research & Development', 'created_at' => now(), 'updated_at' => now()],
            ['division_unit' => 'IT Support', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('position')->insert([
            ['position' => 'Null', 'created_at' => now(), 'updated_at' => now()],
            ['position' => 'Manager', 'created_at' => now(), 'updated_at' => now()],
            ['position' => 'Team Lead', 'created_at' => now(), 'updated_at' => now()],
            ['position' => 'Software Engineer', 'created_at' => now(), 'updated_at' => now()],
            ['position' => 'Intern', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
