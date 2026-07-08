<?php

namespace Database\Factories;

use App\Models\AdoptionRequest;
use App\Models\Organization;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdoptionRequestFactory extends Factory
{
    protected $model = AdoptionRequest::class;

    public function definition(): array
    {
        $pet = Pet::factory()->create();

        return [
            'pet_id' => $pet->id,
            'user_id' => User::factory(),
            'organization_id' => $pet->organization_id,
            'status' => 'pending',
            'message' => fake()->paragraph(),
            'phone' => fake()->phoneNumber(),
            'birth_date' => fake()->date('Y-m-d', '2002-01-01'),
            'address' => fake()->address(),
            'locality' => 'Formosa',
            'province' => 'Formosa',
            'housing_type' => fake()->randomElement(['house', 'apartment']),
            'has_outdoor_space' => fake()->boolean(),
            'has_other_pets' => fake()->boolean(),
            'previous_experience' => fake()->boolean(),
        ];
    }
}
