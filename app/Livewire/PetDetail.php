<?php

namespace App\Livewire;

use App\Models\Pet;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Detalle de mascota')]
#[Layout('layouts.app')]
class PetDetail extends Component
{
    public Pet $pet;

    public function mount(Pet $pet): void
    {
        $this->pet = $pet->load([
            'species', 'breed', 'organization', 'images', 'primaryImage',
            'favorites' => fn($q) => $q->where('user_id', auth()->id()),
        ]);
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
