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

    protected function rules(): array
    {
        return [
            'message' => 'required|string|min:20|max:1000',
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
        ]);

        Flux::toast(variant: 'success', text: __('Solicitud enviada con éxito. El refugio se pondrá en contacto.'));
        $this->reset('message');
    }

    public function render()
    {
        return view('livewire.adoption.create-request');
    }
}
