<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('division_units')->insert([
            ['division_units' => 'Office of the Regional Director', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'Office of the Assistant Regional Director for FOS', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'Office of the Assistant Regional Director for TS', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'ORD - Records Unit', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'ORD - Management Information System', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'ORD - Planning Unit', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'ORD - RRDIC', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'ORD - CIEERDEC', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'ORD - CRHRDC', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'Office of the Assistant Regional Director for FAS', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'FAS - Human Resource', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'FAS - Accounting', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'FAS - Budget', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'FAS - Property & Supply; General Services', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'FAS - Cashier', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'FAS - Gender and Development Unit', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'TS - Disaster Risk Reduction and Management', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'TS - Scholarship', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'TS - S & T Promo', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'TS - Regional Standards and Testing Laboratories', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'TS - Regional Metrology Laboratory', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'TS - Advanced Manufacturing Center', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'TS - P & L', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'TS - R & D', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'TS - FOB', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'TS - Grind', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'TS - LGIA', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'TS - TECHNICAL CONSULTANCY SERVICES', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'TS - MICROBIOLOGICAL TESTING LABORATORY', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'TS - CHEMICAL TESTING LABORATORY', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'TS - SERICULTURE UNIT', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'TS - SPECIAL PROJECTS', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'TS - SETUP', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'TS - OAR TS', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'FOS - SETUP', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'FOS - CEST', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'FOS - SSCP', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'FOS - SSCP', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'PSTO - ABRA', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'PSTO - APAYAO', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'PSTO - BENGUET', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'PSTO - IFUGAO', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'PSTO - KALINGA', 'created_at' => now(), 'updated_at' => now()],
            ['division_units' => 'PSTO - MT. PROVINCE', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('positions')->insert([
            ['positions' => 'Regional Director', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Chief Science Research Specialist', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Chief Administrative Officer', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Supervising Science Research Specialist', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Administrative Officer V', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Senior Science Research Specialist', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Accountant', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Science Research Specialist II', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Science Research Specialist I', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Science Research Analyst', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Administrative Assistant III', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Science Aide', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Administrative Aide IV', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Administrative Aide I', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Project Technical Specialist III', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Project Technical Specialist I', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Science Research Specialist I', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Science Research Specialist I', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Science Research Assistant', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Project Technical Assistant IV', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Project Technical Assistant III', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Project Technical Assistant II', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Project Technical Assistant I', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Project Administrative Aide V', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Project Administrative Aide III', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Project Administrative Aide I', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Project Technical Aide V', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Project Technical Aide III', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Clerk II', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Project Utility Aide II', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Project Laborer I', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'Communications Equipment Operator III', 'created_at' => now(), 'updated_at' => now()],
            ['positions' => 'On-the-Job Trainee', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('users')->insert([
            'employee_id' => '2930184',
            'first_name' => 'Admin',
            'middle_name' => 'DOST',
            'last_name' => 'CAR',
            'gender' => 'Not specified',
            'id_positions' => '29',
            'id_division_units' => '11',
            'employee_type' => 'Regular',
            'username' => 'AdminLJS',
            'email' => 'admin@ljs-dost.gov.ph',
            'password' => Hash::make('admin@ljsDOST'),
            'user_type' => 'admin',
            'created_at' => now(), 
            'updated_at' => now(),
        ]);
    }
}
