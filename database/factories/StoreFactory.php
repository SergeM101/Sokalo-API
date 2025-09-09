<?php

// in database/factories/StoreFactory.php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\StoreCategory;
use App\Enums\StoreVerificationStatus;

class StoreFactory extends Factory
{
    public function definition()    // Define the model's default state.
    {
        return [
            'officialName' => fake()->company() . ' Store',
            'address' => fake()->address(),
            'category' => fake()->randomElement(StoreCategory::class),
            'verificationStatus' => StoreVerificationStatus::APPROVED,
            'contactEmail' => fake()->unique()->companyEmail(),
            'contactPhone' => fake()->unique()->phoneNumber(),
        ];
    }
}
