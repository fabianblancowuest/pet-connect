<?php

namespace App\Livewire\Adoption;

use App\Models\AdoptionRequest;
use Flux\Flux;
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

    public function cancel(int $id): void
    {
        $request = AdoptionRequest::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', AdoptionRequest::STATUS_PENDING)
            ->firstOrFail();

        $request->update(['status' => AdoptionRequest::STATUS_REJECTED]);
        Flux::toast(text: __('Solicitud cancelada.'));
    }

    public function render()
    {
        return view('livewire.adoption.my-requests');
    }
}
