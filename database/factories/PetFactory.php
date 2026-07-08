<?php

namespace Database\Factories;

use App\Models\Breed;
use App\Models\Organization;
use App\Models\Pet;
use App\Models\PetImage;
use App\Models\Species;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PetFactory extends Factory
{
    protected $model = Pet::class;

    public function definition(): array
    {
        $species = Species::inRandomOrder()->first();

        if (!$species) {
            $species = Species::firstOrCreate(['slug' => 'perro'], ['name' => 'Perro']);
        }

        $name = fake()->firstName();

        return [
            'name' => $name,
            'slug' => fn() => Str::slug($name . '-' . Str::random(6)),
            'species_id' => $species->id,
            'breed_id' => function (array $attrs) {
                $speciesId = $attrs['species_id'] ?? Species::inRandomOrder()->first()?->id;
                return Breed::where('species_id', $speciesId)->inRandomOrder()->first()?->id;
            },
            'age_years' => fake()->optional(0.7)->numberBetween(0, 12),
            'age_months' => fake()->optional(0.5)->numberBetween(1, 11),
            'size' => fake()->randomElement(['small', 'medium', 'large']),
            'color' => fake()->optional()->safeColorName(),
            'sex' => fake()->randomElement(['male', 'female']),
            'description' => fake()->paragraphs(2, true),
            'status' => 'available',
            'organization_id' => Organization::factory(),
            'user_id' => User::factory(),
            'is_vaccinated' => fake()->boolean(),
            'is_neutered' => fake()->boolean(),
            'is_house_trained' => fake()->boolean(),
            'good_with_kids' => fake()->optional(0.7)->boolean(),
            'good_with_pets' => fake()->optional(0.7)->boolean(),
        ];
    }

    public function adopted(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'adopted']);
    }

    public function dog(): static
    {
        return $this->state(function (array $attrs) {
            static $speciesId = null;
            $speciesId ??= Species::firstOrCreate(['slug' => 'perro'], ['name' => 'Perro'])->id;

            return ['species_id' => $speciesId];
        });
    }

    public function cat(): static
    {
        return $this->state(function (array $attrs) {
            static $speciesId = null;
            $speciesId ??= Species::firstOrCreate(['slug' => 'gato'], ['name' => 'Gato'])->id;

            return ['species_id' => $speciesId];
        });
    }

    public function small(): static
    {
        return $this->state(fn(array $attrs) => ['size' => 'small']);
    }

    public function medium(): static
    {
        return $this->state(fn(array $attrs) => ['size' => 'medium']);
    }

    public function large(): static
    {
        return $this->state(fn(array $attrs) => ['size' => 'large']);
    }

    public function withImages(int $count = 3): static
    {
        return $this->afterCreating(function (Pet $pet) use ($count) {
            $speciesSlug = $pet->species?->slug === 'gato' ? 'cat' : 'dog';

            PetImage::factory()->{$speciesSlug}()->primary()->create([
                'pet_id' => $pet->id,
                'image_path' => match ($speciesSlug) {
                    'cat' => 'https://loremflickr.com/640/480/cat?lock=' . $pet->id,
                    default => 'https://loremflickr.com/640/480/dog?lock=' . $pet->id,
                },
            ]);

            for ($i = 1; $i < $count; $i++) {
                PetImage::factory()->{$speciesSlug}()->create([
                    'pet_id' => $pet->id,
                    'sort_order' => $i,
                ]);
            }
        });
    }
}
