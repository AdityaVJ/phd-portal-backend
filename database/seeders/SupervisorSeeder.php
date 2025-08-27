<?php

namespace Database\Seeders;

use App\Models\Supervisor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupervisorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supervisor::create([
            'name' => 'Test Supervisor',
            'email' => 'testsupervisor@example.com',
            'type' => 'Assistant Professor',
            'phone' => '9988776655',
            'is_active' => true,
            'password' => bcrypt('password'),
        ]);
    }
}
