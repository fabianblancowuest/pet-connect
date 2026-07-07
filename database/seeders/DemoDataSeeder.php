<?php

namespace Database\Seeders;

use App\Models\AdoptionRequest;
use App\Models\Organization;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'adopter',
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $rescuer = User::factory()->create([
            'name' => 'Refugio Huellitas',
            'email' => 'rescuer@example.com',
            'role' => 'rescuer',
        ]);

        $org = Organization::factory()->create([
            'user_id' => $rescuer->id,
            'name' => 'Huellitas Refugio',
            'slug' => 'huellitas-refugio',
            'city' => 'Formosa',
            'province' => 'Formosa',
        ]);

        Pet::factory()->dog()->small()->withImages(3)->count(4)->create([
            'organization_id' => $org->id,
            'user_id' => $rescuer->id,
        ]);

        Pet::factory()->dog()->medium()->withImages(3)->count(3)->create([
            'organization_id' => $org->id,
            'user_id' => $rescuer->id,
        ]);

        Pet::factory()->dog()->large()->withImages(3)->count(2)->create([
            'organization_id' => $org->id,
            'user_id' => $rescuer->id,
        ]);

        Pet::factory()->cat()->small()->withImages(3)->count(3)->create([
            'organization_id' => $org->id,
            'user_id' => $rescuer->id,
        ]);

        Pet::factory()->cat()->medium()->withImages(3)->count(2)->create([
            'organization_id' => $org->id,
            'user_id' => $rescuer->id,
        ]);

        Pet::factory()->adopted()->withImages(2)->count(3)->create([
            'organization_id' => $org->id,
            'user_id' => $rescuer->id,
        ]);

        $adopter = User::where('email', 'test@example.com')->first();
        $availablePets = Pet::where('organization_id', $org->id)->where('status', 'available')->take(2)->get();

        foreach ($availablePets as $pet) {
            AdoptionRequest::create([
                'pet_id' => $pet->id,
                'user_id' => $adopter->id,
                'organization_id' => $org->id,
                'status' => AdoptionRequest::STATUS_PENDING,
                'phone' => '+54 370 123-4567',
                'birth_date' => '1995-03-15',
                'address' => 'Av. Principal 123, Formosa',
                'housing_type' => 'house',
            ]);
        }
    }
}
