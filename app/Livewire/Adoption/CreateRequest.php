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

    public string $birth_date = '';

    public string $address = '';

    public string $housing_type = '';

    public ?bool $has_outdoor_space = null;

    public array $other_pets_types = [];

    public ?bool $previous_experience = null;

    protected function rules(): array
    {
        return [
            'message' => 'required|string|min:20|max:1000',
            'phone' => 'required|string|max:50',
            'birth_date' => 'required|date|before:today',
            'address' => 'required|string|max:255',
            'housing_type' => 'required|in:house,apartment',
            'has_outdoor_space' => 'required|boolean',
            'other_pets_types' => 'nullable|array',
            'other_pets_types.*' => 'string|in:dog,cat,rodent,bird,other',
            'previous_experience' => 'required|boolean',
        ];
    }

    protected function messages(): array
    {
        return [
            'phone.required' => __('El teléfono es obligatorio.'),
            'birth_date.required' => __('La fecha de nacimiento es obligatoria.'),
            'birth_date.before' => __('La fecha de nacimiento debe ser anterior a hoy.'),
            'address.required' => __('La dirección es obligatoria.'),
            'housing_type.required' => __('Decinos si vivís en casa o departamento.'),
            'has_outdoor_space.required' => __('Indicá si la mascota tendrá acceso a un espacio al aire libre.'),
            'previous_experience.required' => __('Indicá si tenés experiencia previa con mascotas.'),
        ];
    }

    public function updatedHasOutdoorSpace(mixed $value): void
    {
        $this->has_outdoor_space = $value === '' || $value === null ? null : filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public function updatedPreviousExperience(mixed $value): void
    {
        $this->previous_experience = $value === '' || $value === null ? null : filter_var($value, FILTER_VALIDATE_BOOLEAN);
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
            'birth_date' => $this->birth_date,
            'address' => $this->address,
            'housing_type' => $this->housing_type,
            'has_outdoor_space' => $this->has_outdoor_space,
            'has_other_pets' => !empty($this->other_pets_types),
            'other_pets_details' => !empty($this->other_pets_types) ? json_encode($this->other_pets_types) : null,
            'previous_experience' => $this->previous_experience,
        ]);

        Flux::toast(variant: 'success', text: __('Solicitud enviada con éxito. El refugio se pondrá en contacto.'));
        $this->reset('message', 'phone', 'birth_date', 'address', 'housing_type', 'has_outdoor_space', 'other_pets_types', 'previous_experience');
        $this->dispatch('modal-close', name: 'adoption-form');
        $this->dispatch('adoption-request-created');
    }

    public function render()
    {
        return view('livewire.adoption.create-request');
    }
}
