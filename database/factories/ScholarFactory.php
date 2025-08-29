<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Scholar>
 */
class ScholarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->name;
        $email = Str::slug($name, '_') . '_scholar_' . time() . '@example.com';

        return [
            'name' => $name,
            'email' => $email,
            'phone' => $this->faker->phoneNumber,
            'password' => bcrypt('password'),
            'registration_number' => $this->faker->bothify('RJ' . now()->year . '###'),
            'registration_date' => $this->faker->date(),
            'is_active' => true,
            'is_scholarship_complete' => false,
            'gender' => $this->faker->randomElement(['male', 'female']),
            'date_of_birth' => $this->faker->date(),
        ];
    }
}
