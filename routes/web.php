<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\PetCatalog;

Route::view('/', 'welcome')->name('home');

Route::get('/mascotas', PetCatalog::class)->name('pets.catalog');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
