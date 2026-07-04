<?php

namespace App\Livewire\Rescuer;

use App\Models\Organization;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Mi refugio')]
#[Layout('layouts.app')]
class OrganizationSettings extends Component
{
    public ?Organization $organization = null;

    public string $name = '';

    public ?string $description = null;

    public ?string $phone = null;

    public ?string $address = null;

    public ?string $city = null;

    public ?string $province = null;

    public ?string $website = null;

    public bool $editing = false;

    public function mount(): void
    {
        $this->organization = auth()->user()->organizations()->first();

        if ($this->organization) {
            $this->editing = true;
            $this->name = $this->organization->name;
            $this->description = $this->organization->description;
            $this->phone = $this->organization->phone;
            $this->address = $this->organization->address;
            $this->city = $this->organization->city;
            $this->province = $this->organization->province;
            $this->website = $this->organization->website;
        }
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'website' => 'nullable|url|max:255',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'province' => $this->province,
            'website' => $this->website,
        ];

        if ($this->editing) {
            $this->organization->update($data);
            Flux::toast(variant: 'success', text: __('Refugio actualizado.'));
        } else {
            $data['user_id'] = auth()->id();
            $data['status'] = 'active';
            Organization::create($data);
            Flux::toast(variant: 'success', text: __('Refugio creado con éxito.'));
            $this->redirect(route('rescuer.organization'), navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.rescuer.organization-settings');
    }
}
