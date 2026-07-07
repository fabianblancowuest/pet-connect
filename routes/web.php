<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\PetCatalog;
use App\Livewire\PetDetail;
use App\Livewire\Adoption\MyRequests;
use App\Livewire\Adoption\ManageRequests;
use App\Livewire\Rescuer\PetManager;
use App\Livewire\Rescuer\PetForm;
use App\Livewire\Rescuer\OrganizationSettings;

Route::view('/', 'welcome')->name('home');
Route::view('/quienes-somos', 'about')->name('about');

Route::get('/mascotas', PetCatalog::class)->name('pets.catalog');
Route::get('/mascotas/{pet:slug}', PetDetail::class)->name('pets.detail');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::prefix('adopciones')->name('adoption.')->group(function () {
        Route::get('/mis-solicitudes', MyRequests::class)->name('my-requests');
    });

    Route::middleware(['role:rescuer,admin'])->prefix('rescuer')->name('rescuer.')->group(function () {
        Route::get('/solicitudes', ManageRequests::class)->name('requests');
        Route::get('/mascotas', PetManager::class)->name('pets.index');
        Route::get('/mascotas/crear', PetForm::class)->name('pets.create');
        Route::get('/mascotas/{pet}/editar', PetForm::class)->name('pets.edit');
        Route::get('/refugio', OrganizationSettings::class)->name('organization');
    });
});

require __DIR__.'/settings.php';
