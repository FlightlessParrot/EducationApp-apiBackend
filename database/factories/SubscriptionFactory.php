<?php

namespace Database\Factories;

use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date=new DateTime();
        $date->modify('+1 year');
        return [
           'name'=>fake()->sentence(),
           'price'=>fake()->randomFloat(2,5,8),
            'license_duration'=>$date,
            'active'=>true,
            'description'=>fake()->text()
        ];
    }
}
