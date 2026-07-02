<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\PetImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetImageFactory extends Factory
{
    protected $model = PetImage::class;

    public function definition(): array
    {
        return [
            'pet_id' => Pet::factory(),
            'image_path' => 'pets/' . fake()->imageUrl(640, 480, 'animals', true),
            'is_primary' => false,
            'sort_order' => 0,
        ];
    }

    public function primary(): static
    {
        return $this->state(fn(array $attrs) => ['is_primary' => true, 'sort_order' => 0]);
    }
}
