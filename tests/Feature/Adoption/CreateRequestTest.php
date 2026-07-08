<?php

use App\Livewire\Adoption\CreateRequest;
use App\Models\AdoptionRequest;
use App\Models\Pet;
use App\Models\User;
use Database\Seeders\SpeciesSeeder;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(SpeciesSeeder::class);

    Http::fake([
        'apis.datos.gob.ar/georef/api/localidades*' => Http::response([
            'localidades' => [
                ['id' => '34014020', 'nombre' => 'Formosa'],
                ['id' => '34049010', 'nombre' => 'Clorinda'],
                ['id' => '34056040', 'nombre' => 'Pirané'],
            ],
            'total' => 3,
        ]),
    ]);
});

test('mounts with localities loaded from Georef', function () {
    $user = User::factory()->create(['role' => 'adopter']);
    $pet = Pet::factory()->create();

    $component = Livewire::actingAs($user)->test(CreateRequest::class, ['pet' => $pet]);

    $component->assertSet('localities', [
        '34014020' => 'Formosa',
        '34049010' => 'Clorinda',
        '34056040' => 'Pirané',
    ]);
});

test('pre-fills personal data from previous request', function () {
    $user = User::factory()->create(['role' => 'adopter']);
    $pet = Pet::factory()->create();

    AdoptionRequest::factory()->create([
        'user_id' => $user->id,
        'pet_id' => $pet->id,
        'phone' => '+54 370 123-4567',
        'birth_date' => '1990-05-15',
        'address' => 'Av. 25 de Mayo 123',
        'locality' => 'Formosa',
        'province' => 'Formosa',
        'housing_type' => 'house',
    ]);

    $component = Livewire::actingAs($user)->test(CreateRequest::class, ['pet' => $pet]);

    $component->assertSet('hasPreviousRequests', true)
        ->assertSet('phone', '+54 370 123-4567')
        ->assertSet('birth_date', '1990-05-15')
        ->assertSet('address', 'Av. 25 de Mayo 123')
        ->assertSet('locality', 'Formosa')
        ->assertSet('province', 'Formosa')
        ->assertSet('housing_type', 'house');
});

test('requires personal data for first-time requesters', function () {
    $user = User::factory()->create(['role' => 'adopter']);
    $pet = Pet::factory()->create();

    Livewire::actingAs($user)
        ->test(CreateRequest::class, ['pet' => $pet])
        ->set('message', 'too short')
        ->call('submit')
        ->assertHasErrors([
            'message', 'phone', 'birth_date', 'address', 'locality',
            'housing_type', 'has_outdoor_space', 'previous_experience',
        ]);
});

test('validates message length', function () {
    $user = User::factory()->create(['role' => 'adopter']);
    $pet = Pet::factory()->create();

    Livewire::actingAs($user)
        ->test(CreateRequest::class, ['pet' => $pet])
        ->set('message', 'Corta')
        ->call('submit')
        ->assertHasErrors(['message']);
});

test('creates adoption request with locality and province', function () {
    $user = User::factory()->create(['role' => 'adopter']);
    $pet = Pet::factory()->create();

    Livewire::actingAs($user)
        ->test(CreateRequest::class, ['pet' => $pet])
        ->set('message', 'Quiero adoptar porque tengo experiencia con mascotas y un hogar adecuado.')
        ->set('phone', '+54 370 123-4567')
        ->set('birth_date', '1990-05-15')
        ->set('address', 'Av. 25 de Mayo 123')
        ->set('locality', 'Formosa')
        ->set('housing_type', 'house')
        ->set('has_outdoor_space', true)
        ->set('previous_experience', true)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertDispatched('adoption-request-created')
        ->assertDispatched('modal-close');

    $this->assertDatabaseHas('adoption_requests', [
        'user_id' => $user->id,
        'pet_id' => $pet->id,
        'locality' => 'Formosa',
        'province' => 'Formosa',
        'status' => 'pending',
    ]);
});

test('prevents duplicate active requests for the same pet', function () {
    $user = User::factory()->create(['role' => 'adopter']);
    $pet = Pet::factory()->create();

    AdoptionRequest::factory()->create([
        'user_id' => $user->id,
        'pet_id' => $pet->id,
        'status' => 'pending',
    ]);

    Livewire::actingAs($user)
        ->test(CreateRequest::class, ['pet' => $pet])
        ->set('message', 'Quiero adoptar porque tengo experiencia con mascotas y un hogar adecuado.')
        ->set('phone', '+54 370 123-4567')
        ->set('birth_date', '1990-05-15')
        ->set('address', 'Av. 25 de Mayo 123')
        ->set('locality', 'Formosa')
        ->set('housing_type', 'house')
        ->set('has_outdoor_space', true)
        ->set('previous_experience', true)
        ->call('submit');

    $this->assertDatabaseCount('adoption_requests', 1);
});

test('rejects submission when pet is not available', function () {
    $user = User::factory()->create(['role' => 'adopter']);
    $pet = Pet::factory()->adopted()->create();

    Livewire::actingAs($user)
        ->test(CreateRequest::class, ['pet' => $pet])
        ->set('message', 'Quiero adoptar porque tengo experiencia con mascotas y un hogar adecuado.')
        ->set('phone', '+54 370 123-4567')
        ->set('birth_date', '1990-05-15')
        ->set('address', 'Av. 25 de Mayo 123')
        ->set('locality', 'Formosa')
        ->set('housing_type', 'house')
        ->set('has_outdoor_space', true)
        ->set('previous_experience', true)
        ->call('submit');

    $this->assertDatabaseCount('adoption_requests', 0);
});

test('rejects submission by admin users', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $pet = Pet::factory()->create();

    Livewire::actingAs($admin)
        ->test(CreateRequest::class, ['pet' => $pet])
        ->set('message', 'Quiero adoptar porque tengo experiencia con mascotas y un hogar adecuado.')
        ->set('phone', '+54 370 123-4567')
        ->set('birth_date', '1990-05-15')
        ->set('address', 'Av. 25 de Mayo 123')
        ->set('locality', 'Formosa')
        ->set('housing_type', 'house')
        ->set('has_outdoor_space', true)
        ->set('previous_experience', true)
        ->call('submit');

    $this->assertDatabaseCount('adoption_requests', 0);
});
