<?php

namespace App\Livewire\Rescuer;

use App\Models\Breed;
use App\Models\Pet;
use App\Models\PetImage;
use App\Models\Species;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
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

    public ?int $adopted_by_user_id = null;

    public bool $editing = false;

    public function title(): string
    {
        return $this->editing ? __('Editar mascota') : __('Nueva mascota');
    }

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
            $this->adopted_by_user_id = $pet->adopted_by_user_id;
        }
    }

    #[Computed]
    public function users()
    {
        return \App\Models\User::orderBy('name')->get();
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'species_id' => 'required|exists:species,id',
            'breed_id' => [
                'nullable',
                Rule::exists('breeds', 'id')->where(fn ($q) => $q->where('species_id', $this->species_id)),
            ],
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
            'images' => 'nullable|array|max:10',
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

        DB::transaction(function () use ($org) {
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

            if ($this->status === 'adopted') {
                $data['adopted_by_user_id'] = $this->adopted_by_user_id;
            } else {
                $data['adopted_by_user_id'] = null;
            }

            if ($this->editing) {
                $this->authorize('update', $this->pet);

                $this->pet->update($data);
                Flux::toast(variant: 'success', text: __('Mascota actualizada.'));
            } else {
                $data['organization_id'] = $org->id;
                $data['user_id'] = auth()->id();
                $this->pet = Pet::create($data);
                Flux::toast(variant: 'success', text: __('Mascota creada con éxito.'));
            }

            $nextSortOrder = $this->pet->images()->max('sort_order') + 1;

            foreach ($this->images as $image) {
                $path = $image->store('pets', 'public');
                $this->pet->images()->create([
                    'image_path' => Storage::url($path),
                    'is_primary' => !$this->pet->images()->where('is_primary', true)->exists() && $nextSortOrder === 1,
                    'sort_order' => $nextSortOrder++,
                ]);
            }
        });

        $this->redirect(route('rescuer.pets.index'), navigate: true);
    }

    public function deleteImage(int $imageId): void
    {
        $image = PetImage::findOrFail($imageId);
        $this->authorize('update', $image->pet);

        $wasPrimary = $image->is_primary;

        $image->delete();

        if ($wasPrimary) {
            $nextImage = $this->pet->images()->orderBy('sort_order')->first();
            if ($nextImage) {
                $nextImage->update(['is_primary' => true]);
            }
        }

        Flux::toast(text: __('Imagen eliminada.'));
    }

    public function setPrimaryImage(int $imageId): void
    {
        $image = PetImage::findOrFail($imageId);
        $this->authorize('update', $image->pet);

        $this->pet->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        Flux::toast(variant: 'success', text: __('Imagen principal actualizada.'));
    }

    public function render()
    {
        return view('livewire.rescuer.pet-form');
    }
}
