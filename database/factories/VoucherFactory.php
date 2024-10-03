<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voucher>
 */
class VoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->bothify('##??##')),
            'discount_amount' => rand(50, 100) * 100,  
            'is_active' => true, 
            'start_date' => now(),
            'max_redeem' => rand(1,100),
            'redeem_count' => 0,
            'end_date' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
        ];
    }
}
