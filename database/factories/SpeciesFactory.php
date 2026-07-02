<?php

namespace Database\Factories;

use App\Models\Species;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpeciesFactory extends Factory
{
    protected $model = Species::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'slug' => fn(array $attrs) => str($attrs['name'])->slug(),
        ];
    }
}
