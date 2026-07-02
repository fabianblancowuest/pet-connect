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

        Pet::factory()->dog()->small()->count(4)->create([
            'organization_id' => $org->id,
            'user_id' => $rescuer->id,
        ]);

        Pet::factory()->dog()->medium()->count(3)->create([
            'organization_id' => $org->id,
            'user_id' => $rescuer->id,
        ]);

        Pet::factory()->dog()->large()->count(2)->create([
            'organization_id' => $org->id,
            'user_id' => $rescuer->id,
        ]);

        Pet::factory()->cat()->small()->count(3)->create([
            'organization_id' => $org->id,
            'user_id' => $rescuer->id,
        ]);

        Pet::factory()->cat()->medium()->count(2)->create([
            'organization_id' => $org->id,
            'user_id' => $rescuer->id,
        ]);

        Pet::factory()->adopted()->count(3)->create([
            'organization_id' => $org->id,
            'user_id' => $rescuer->id,
        ]);
    }
}
