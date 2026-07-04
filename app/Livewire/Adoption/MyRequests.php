<?php

namespace App\Livewire\Adoption;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Mis solicitudes de adopción')]
#[Layout('layouts.app')]
class MyRequests extends Component
{
    #[Computed]
    public function requests()
    {
        return auth()->user()
            ->adoptionRequests()
            ->with(['pet.species', 'pet.breed', 'pet.primaryImage', 'organization'])
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.adoption.my-requests');
    }
}
