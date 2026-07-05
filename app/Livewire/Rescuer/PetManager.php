<?php

namespace App\Livewire\Rescuer;

use App\Models\Pet;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Mis mascotas')]
#[Layout('layouts.app')]
class PetManager extends Component
{
    use WithPagination;

    public ?string $statusFilter = null;

    protected $queryString = ['statusFilter' => ['except' => '']];

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function pets()
    {
        $orgIds = auth()->user()->organizations()->pluck('id');

        return Pet::query()
            ->whereIn('organization_id', $orgIds)
            ->with(['species', 'breed', 'primaryImage'])
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(12);
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
        return view('livewire.rescuer.pet-manager', [
            'pets' => $this->pets(),
        ]);
    }
}
