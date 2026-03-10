<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'employee_id'       => $this->faker->unique()->numerify('EMP-####'),
            'first_name'        => $this->faker->firstName(),
            'middle_name'       => $this->faker->optional()->lastName(),
            'last_name'         => $this->faker->lastName(),
            'gender'            => $this->faker->randomElement(['Male', 'Female']),
            'id_positions'      => \App\Models\Position::factory(),
            'id_division_units' => \App\Models\DivisionUnit::factory(),
            'employee_type'     => $this->faker->randomElement(['Regular', 'Contractual']),
            'username'          => $this->faker->unique()->userName(),
            'email'             => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => 'password',
            'last_login'        => now(),
            'is_active'         => true,
            'is_archived'       => false,
            'user_type'         => 'user',
            'remember_token'    => Str::random(10),
        ];
    }
}