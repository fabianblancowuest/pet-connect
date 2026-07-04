<?php

namespace App\Livewire\Adoption;

use App\Models\Pet;
use Flux\Flux;
use Livewire\Component;

class CreateRequest extends Component
{
    public Pet $pet;

    public string $message = '';

    public bool $showForm = false;

    protected function rules(): array
    {
        return [
            'message' => 'required|string|min:20|max:1000',
        ];
    }

    public function submit(): void
    {
        $this->validate();

        auth()->user()->adoptionRequests()->create([
            'pet_id' => $this->pet->id,
            'organization_id' => $this->pet->organization_id,
            'status' => 'pending',
            'message' => $this->message,
        ]);

        Flux::toast(variant: 'success', text: __('Solicitud enviada con éxito. El refugio se pondrá en contacto.'));
        $this->reset('message', 'showForm');
    }

    public function render()
    {
        return view('livewire.adoption.create-request');
    }
}
