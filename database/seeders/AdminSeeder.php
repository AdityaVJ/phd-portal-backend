<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Soham Banerjee',
            'email' => 'soham@renderbit.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        Admin::create([
            'name' => 'Shreya Pramanik',
            'email' => 'shreya@renderbit.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        Admin::create([
            'name' => 'Aditya V Jajodia',
            'email' => 'aditya@renderbit.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
    }
}
