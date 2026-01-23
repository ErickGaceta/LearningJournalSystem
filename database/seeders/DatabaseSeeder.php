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
        DB::table('division_units')->insert([
            ['division_units' => 'Null', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'Finance', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'Human Resources', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'Research & Development', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'IT Support', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('positions')->insert([
            ['positions' => 'Null', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Manager', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Team Lead', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Software Engineer', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Intern', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
