<?php

namespace App\Livewire\Admin;

use App\Models\Organization;
use App\Models\Pet;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Todas las mascotas')]
#[Layout('layouts.app')]
class PetManager extends Component
{
    use WithPagination;

    public ?string $statusFilter = null;

    public ?int $organizationFilter = null;

    public string $search = '';

    public ?Pet $previewPet = null;

    protected $queryString = [
        'statusFilter' => ['except' => ''],
        'organizationFilter' => ['except' => ''],
        'search' => ['except' => ''],
    ];

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingOrganizationFilter(): void
    {
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function pets()
    {
        return Pet::query()
            ->with(['species', 'breed', 'primaryImage', 'organization'])
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->organizationFilter, fn($q) => $q->where('organization_id', $this->organizationFilter))
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->latest()
            ->paginate(12);
    }

    public function showPreview(int $id): void
    {
        $this->previewPet = Pet::with([
            'species', 'breed', 'images', 'primaryImage', 'organization',
        ])->findOrFail($id);
    }

    public function closePreview(): void
    {
        $this->previewPet = null;
    }

    public function deletePet(int $id): void
    {
        $pet = Pet::with('images')->findOrFail($id);

        foreach ($pet->images as $image) {
            $image->delete();
        }

        $pet->delete();
        Flux::toast(text: __('Mascota eliminada.'));
    }

    public function render()
    {
        return view('livewire.admin.pet-manager', [
            'pets' => $this->pets(),
            'organizations' => Organization::orderBy('name')->get(),
        ]);
    }
}
