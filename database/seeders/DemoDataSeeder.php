<?php

namespace Database\Seeders;

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
            'city' => 'Buenos Aires',
            'province' => 'CABA',
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
    }
}
