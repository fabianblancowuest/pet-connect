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
            'image_path' => fake()->imageUrl(640, 480, 'animals', true),
            'is_primary' => false,
            'sort_order' => 0,
        ];
    }

    public function primary(): static
    {
        return $this->state(fn(array $attrs) => ['is_primary' => true, 'sort_order' => 0]);
    }

    public function dog(): static
    {
        return $this->state(fn(array $attrs) => [
            'image_path' => 'https://loremflickr.com/640/480/dog?lock=' . random_int(1, 9999),
        ]);
    }

    public function cat(): static
    {
        return $this->state(fn(array $attrs) => [
            'image_path' => 'https://loremflickr.com/640/480/cat?lock=' . random_int(1, 9999),
        ]);
    }
}
