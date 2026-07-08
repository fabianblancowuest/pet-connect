<?php

namespace App\Livewire;

use App\Models\AdoptionRequest;
use App\Models\Pet;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Detalle de mascota')]
#[Layout('layouts.app')]
class PetDetail extends Component
{
    public Pet $pet;

    public int $userRequestCount = 0;

    public bool $hasRequestForThisPet = false;

    public function mount(Pet $pet): void
    {
        $this->pet = $pet->load([
            'species', 'breed', 'organization', 'images', 'primaryImage',
            'favorites' => fn($q) => $q->where('user_id', auth()->id()),
        ]);

        $this->checkPendingRequest();
    }

    #[On('adoption-request-created')]
    public function checkPendingRequest(): void
    {
        if (!auth()->check()) {
            $this->userRequestCount = 0;
            $this->hasRequestForThisPet = false;
            return;
        }

        $this->userRequestCount = auth()->user()->adoptionRequests()
            ->whereIn('status', [AdoptionRequest::STATUS_PENDING, AdoptionRequest::STATUS_IN_PROGRESS, AdoptionRequest::STATUS_APPROVED])
            ->count();

        $this->hasRequestForThisPet = auth()->user()->adoptionRequests()
            ->where('pet_id', $this->pet->id)
            ->whereIn('status', [AdoptionRequest::STATUS_PENDING, AdoptionRequest::STATUS_IN_PROGRESS, AdoptionRequest::STATUS_APPROVED])
            ->exists();
    }

    public function toggleFavorite(): void
    {
        if (!auth()->check()) {
            $this->redirect(route('login'), navigate: true);
            return;
        }

        $favorite = $this->pet->favorites()
            ->where('user_id', auth()->id())
            ->first();

        if ($favorite) {
            $favorite->delete();
        } else {
            $this->pet->favorites()->create(['user_id' => auth()->id()]);
        }

        $this->pet->load(['favorites' => fn($q) => $q->where('user_id', auth()->id())]);
    }

    public function render()
    {
        return view('livewire.pet-detail');
    }
}
