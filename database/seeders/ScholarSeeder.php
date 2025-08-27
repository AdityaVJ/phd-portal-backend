<?php

namespace Database\Seeders;

use App\Models\Scholar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;

class ScholarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Scholar::create([
            'name' => 'Test Scholar',
            'email' => 'testscholar@example.com',
            'registration_number' => 'TEST2025001',
            'registration_date' => Date::create('2025', '01', '01'),
            'password' => bcrypt('password'),
            'gender' => 'male',
            'date_of_birth' => Date::create('1996', '01', '01')
        ]);
    }
}
