<?php

namespace Database\Seeders;

use App\Models\Breed;
use App\Models\Species;
use Illuminate\Database\Seeder;

class SpeciesSeeder extends Seeder
{
    public function run(): void
    {
        $dog = Species::create(['name' => 'Perro', 'slug' => 'perro', 'description' => 'Perros domésticos']);
        $cat = Species::create(['name' => 'Gato', 'slug' => 'gato', 'description' => 'Gatos domésticos']);
        $rabbit = Species::create(['name' => 'Conejo', 'slug' => 'conejo', 'description' => 'Conejos domésticos']);
        $bird = Species::create(['name' => 'Ave', 'slug' => 'ave', 'description' => 'Aves domésticas']);
        $rodent = Species::create(['name' => 'Roedor', 'slug' => 'roedor', 'description' => 'Roedores y hámsters']);

        $dogBreeds = [
            'Labrador Retriever', 'Pastor Alemán', 'Golden Retriever', 'Bulldog',
            'Beagle', 'Poodle', 'Chihuahua', 'Husky Siberiano',
            'Dálmata', 'Boxer', 'Shih Tzu', 'Caniche',
            'Mestizo', 'Callejero',
        ];

        $catBreeds = [
            'Siamés', 'Persa', 'Maine Coon', 'Bengalí',
            'Sphynx', 'Angora', 'Mestizo', 'Callejero',
        ];

        $rabbitBreeds = [
            'Holandés', 'Mini Lop', 'Rex', 'Angora',
            'Cabeza de León', 'Mestizo',
        ];

        $birdBreeds = [
            'Periquito Australiano', 'Canario', 'Cacatúa', 'Agapornis',
            'Ninfa', 'Loro',
        ];

        $rodentBreeds = [
            'Hámster Sirio', 'Hámster Enano', 'Cobaya', 'Jerbo',
            'Ratón Doméstico',
        ];

        $createBreeds = function (Species $species, array $breeds): void {
            foreach ($breeds as $breed) {
                Breed::create([
                    'species_id' => $species->id,
                    'name' => $breed,
                    'slug' => $species->slug . '-' . str($breed)->slug(),
                ]);
            }
        };

        $createBreeds($dog, $dogBreeds);
        $createBreeds($cat, $catBreeds);
        $createBreeds($rabbit, $rabbitBreeds);
        $createBreeds($bird, $birdBreeds);
        $createBreeds($rodent, $rodentBreeds);
    }
}
