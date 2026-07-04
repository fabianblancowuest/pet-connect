<?php

namespace App\Livewire\Rescuer;

use App\Models\Breed;
use App\Models\Pet;
use App\Models\Species;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class PetForm extends Component
{
    use WithFileUploads;

    public ?Pet $pet = null;

    public string $name = '';

    public ?int $species_id = null;

    public ?int $breed_id = null;

    public ?int $age_years = null;

    public ?int $age_months = null;

    public string $size = 'medium';

    public ?string $color = null;

    public string $description = '';

    public string $status = 'available';

    public bool $is_vaccinated = false;

    public bool $is_neutered = false;

    public bool $is_house_trained = false;

    public ?bool $good_with_kids = null;

    public ?bool $good_with_pets = null;

    public $images = [];

    public bool $editing = false;

    public function mount(?Pet $pet = null): void
    {
        $this->pet = $pet;

        if ($pet && $pet->exists) {
            $this->editing = true;
            $this->name = $pet->name;
            $this->species_id = $pet->species_id;
            $this->breed_id = $pet->breed_id;
            $this->age_years = $pet->age_years;
            $this->age_months = $pet->age_months;
            $this->size = $pet->size;
            $this->color = $pet->color;
            $this->description = $pet->description;
            $this->status = $pet->status;
            $this->is_vaccinated = $pet->is_vaccinated;
            $this->is_neutered = $pet->is_neutered;
            $this->is_house_trained = $pet->is_house_trained;
            $this->good_with_kids = $pet->good_with_kids;
            $this->good_with_pets = $pet->good_with_pets;
            $this->breed_id = $pet->breed_id;
        }
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'species_id' => 'required|exists:species,id',
            'breed_id' => 'nullable|exists:breeds,id',
            'age_years' => 'nullable|integer|min:0|max:50',
            'age_months' => 'nullable|integer|min:0|max:11',
            'size' => 'required|in:small,medium,large',
            'color' => 'nullable|string|max:100',
            'description' => 'required|string|max:5000',
            'status' => 'required|in:available,adopted',
            'is_vaccinated' => 'boolean',
            'is_neutered' => 'boolean',
            'is_house_trained' => 'boolean',
            'good_with_kids' => 'nullable|boolean',
            'good_with_pets' => 'nullable|boolean',
            'images.*' => 'nullable|image|max:2048',
        ];
    }

    #[Computed]
    public function speciesList()
    {
        return Species::orderBy('name')->get();
    }

    #[Computed]
    public function breeds()
    {
        if (!$this->species_id) {
            return collect();
        }
        return Breed::where('species_id', $this->species_id)->orderBy('name')->get();
    }

    public function updatedSpeciesId(): void
    {
        $this->breed_id = null;
    }

    public function save(): void
    {
        $this->validate();

        $org = auth()->user()->organizations()->first();

        if (!$org) {
            Flux::toast(variant: 'error', text: __('Primero debés crear un refugio.'));
            return;
        }

        $data = [
            'name' => $this->name,
            'species_id' => $this->species_id,
            'breed_id' => $this->breed_id,
            'age_years' => $this->age_years,
            'age_months' => $this->age_months,
            'size' => $this->size,
            'color' => $this->color,
            'description' => $this->description,
            'status' => $this->status,
            'is_vaccinated' => $this->is_vaccinated,
            'is_neutered' => $this->is_neutered,
            'is_house_trained' => $this->is_house_trained,
            'good_with_kids' => $this->good_with_kids,
            'good_with_pets' => $this->good_with_pets,
        ];

        if ($this->editing) {
            $this->pet->update($data);
            Flux::toast(variant: 'success', text: __('Mascota actualizada.'));
        } else {
            $data['organization_id'] = $org->id;
            $data['user_id'] = auth()->id();
            $this->pet = Pet::create($data);
            Flux::toast(variant: 'success', text: __('Mascota creada con éxito.'));
        }

        foreach ($this->images as $image) {
            $path = $image->store('pets', 'public');
            $isPrimary = !$this->pet->images()->exists();
            $this->pet->images()->create([
                'image_path' => '/storage/' . $path,
                'is_primary' => $isPrimary,
                'sort_order' => $this->pet->images()->count(),
            ]);
        }

        $this->redirect(route('rescuer.pets.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.rescuer.pet-form');
    }
}
