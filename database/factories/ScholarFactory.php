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

    public function configure()
    {
        return $this->afterCreating(function (\App\Models\Scholar $scholar) {
            $scholar->saveDetails([
                'secondary_school_name' => fake()->company,
                'secondary_school_year' => fake()->year,
                'secondary_school_subjects' => fake()->words(3, true),
                'secondary_school_aggregate' => fake()->randomFloat(2, 50, 100),
                'secondary_school_grade' => fake()->randomElement(['A', 'B', 'C']),
                'secondary_school_board' => fake()->company,
                'hs_school_name' => fake()->company,
                'hs_school_year' => fake()->year,
                'hs_school_board' => fake()->company,
                'hs_school_subjects' => fake()->words(3, true),
                'hs_school_aggregate' => fake()->randomFloat(2, 50, 100),
                'hs_school_grade' => fake()->randomElement(['A', 'B', 'C']),
                'grad_course' => fake()->word,
                'grad_pass_year' => fake()->year,
                'grad_university' => fake()->company,
                'grad_aggregate' => fake()->randomFloat(2, 50, 100),
                'grad_subject' => fake()->word,
                'grad_grade' => fake()->randomElement(['A', 'B', 'C']),
                'post_grad_course' => fake()->word,
                'post_grad_pass_year' => fake()->year,
                'post_grad_university' => fake()->company,
                'post_grad_aggregrate' => fake()->randomFloat(2, 50, 100),
                'post_grad_subject' => fake()->word,
                'post_grad_grade' => fake()->randomElement(['A', 'B', 'C']),
                'address' => fake()->address,
                'city' => fake()->city,
                'state' => fake()->state,
                'pincode' => fake()->postcode,
            ]);
        });
    }

}
