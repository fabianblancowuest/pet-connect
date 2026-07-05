<?php

namespace App\Livewire;

use App\Models\Pet;
use App\Models\Species;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Mascotas en adopción')]
#[Layout('layouts.app')]
class PetCatalog extends Component
{
    use WithPagination;

    public string $search = '';

    public ?string $species = null;

    public ?string $size = null;

    public ?string $status = 'available';

    public string $sort = 'latest';

    protected function queryString(): array
    {
        return [
            'search' => ['except' => ''],
            'species' => ['except' => ''],
            'size' => ['except' => ''],
            'status' => ['except' => 'available'],
            'sort' => ['except' => 'latest'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSpecies(): void
    {
        $this->resetPage();
    }

    public function updatingSize(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'species', 'size', 'status', 'sort']);
        $this->resetPage();
    }

    public function redirectToDetail(string $slug): void
    {
        $this->redirect(route('pets.detail', Pet::where('slug', $slug)->firstOrFail()), navigate: true);
    }

    #[Computed]
    public function speciesList()
    {
        return Species::withCount('pets')->orderBy('name')->get();
    }

    #[Computed]
    public function pets()
    {
        return Pet::query()
            ->with(['species', 'breed', 'organization', 'primaryImage'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->species, function ($query) {
                $query->whereHas('species', fn($q) => $q->where('slug', $this->species));
            })
            ->when($this->size, function ($query) {
                $query->where('size', $this->size);
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->sort === 'latest', fn($query) => $query->latest())
            ->when($this->sort === 'oldest', fn($query) => $query->oldest())
            ->when($this->sort === 'name', fn($query) => $query->orderBy('name'))
            ->paginate(12);
    }

    public function render()
    {
        return view('livewire.pet-catalog');
    }
}
