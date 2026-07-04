<?php

namespace App\Livewire\Rescuer;

use App\Models\Pet;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Mis mascotas')]
#[Layout('layouts.app')]
class PetManager extends Component
{
    public ?string $statusFilter = null;

    #[Computed]
    public function pets()
    {
        $orgIds = auth()->user()->organizations()->pluck('id');

        return Pet::query()
            ->whereIn('organization_id', $orgIds)
            ->with(['species', 'breed', 'primaryImage'])
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->get();
    }

    public function deletePet(int $id): void
    {
        $pet = Pet::findOrFail($id);
        $orgIds = auth()->user()->organizations()->pluck('id');
        abort_unless($orgIds->contains($pet->organization_id), 403);
        $pet->delete();
        Flux::toast(text: __('Mascota eliminada.'));
    }

    public function render()
    {
        return view('livewire.rescuer.pet-manager');
    }
}
