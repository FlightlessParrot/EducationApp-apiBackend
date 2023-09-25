<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserAdress>
 */
class UserAdressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   
    public function definition(): array
    {
        return [
            'nip'=>fake()->numberBetween(10000000,20000000),
            'adress'=>fake()->address(),
            'city'=>fake()->city(),
            'postal_code'=>fake()->numberBetween(10000,99999),
        ];
    }
}
