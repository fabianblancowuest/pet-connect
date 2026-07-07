<?php

namespace App\Livewire\Adoption;

use App\Models\AdoptionRequest;
use App\Models\Pet;
use Flux\Flux;
use Livewire\Component;

class CreateRequest extends Component
{
    public Pet $pet;

    public string $message = '';

    public string $phone = '';

    public string $housing_type = '';

    public ?bool $has_outdoor_space = null;

    public bool $has_other_pets = false;

    public ?string $other_pets_details = null;

    public bool $has_children = false;

    public ?string $children_ages = null;

    public ?bool $previous_experience = null;

    protected function rules(): array
    {
        return [
            'message' => 'required|string|min:20|max:1000',
            'phone' => 'required|string|max:50',
            'housing_type' => 'required|in:house,apartment',
            'has_outdoor_space' => 'required|boolean',
            'has_other_pets' => 'required|boolean',
            'other_pets_details' => 'nullable|string|max:500',
            'has_children' => 'required|boolean',
            'children_ages' => 'nullable|string|max:200',
            'previous_experience' => 'required|boolean',
        ];
    }

    protected function messages(): array
    {
        return [
            'phone.required' => __('El teléfono es obligatorio.'),
            'housing_type.required' => __('Decinos si vivís en casa o departamento.'),
            'has_outdoor_space.required' => __('Indicá si la mascota tendrá acceso a un espacio al aire libre.'),
            'has_other_pets.required' => __('Indicá si tenés otras mascotas.'),
            'has_children.required' => __('Indicá si hay niños en el hogar.'),
            'previous_experience.required' => __('Indicá si tenés experiencia previa con mascotas.'),
        ];
    }

    public function submit(): void
    {
        $this->validate();

        if ($this->pet->status !== Pet::STATUS_AVAILABLE) {
            Flux::toast(variant: 'error', text: __('Esta mascota ya no está disponible para adopción.'));
            return;
        }

        $existing = auth()->user()->adoptionRequests()
            ->where('pet_id', $this->pet->id)
            ->whereIn('status', [AdoptionRequest::STATUS_PENDING, AdoptionRequest::STATUS_IN_PROGRESS, AdoptionRequest::STATUS_APPROVED])
            ->exists();

        if ($existing) {
            Flux::toast(variant: 'error', text: __('Ya tenés una solicitud activa para esta mascota.'));
            return;
        }

        auth()->user()->adoptionRequests()->create([
            'pet_id' => $this->pet->id,
            'organization_id' => $this->pet->organization_id,
            'status' => 'pending',
            'message' => $this->message,
            'phone' => $this->phone,
            'housing_type' => $this->housing_type,
            'has_outdoor_space' => $this->has_outdoor_space,
            'has_other_pets' => $this->has_other_pets,
            'other_pets_details' => $this->has_other_pets ? $this->other_pets_details : null,
            'has_children' => $this->has_children,
            'children_ages' => $this->has_children ? $this->children_ages : null,
            'previous_experience' => $this->previous_experience,
        ]);

        Flux::toast(variant: 'success', text: __('Solicitud enviada con éxito. El refugio se pondrá en contacto.'));
        $this->reset();
    }

    public function render()
    {
        return view('livewire.adoption.create-request');
    }
}
