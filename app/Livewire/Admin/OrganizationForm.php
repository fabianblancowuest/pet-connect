<?php

namespace App\Livewire\Admin;

use App\Models\Organization;
use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Refugio')]
#[Layout('layouts.app')]
class OrganizationForm extends Component
{
    use WithFileUploads;

    public ?Organization $organization = null;

    public string $name = '';

    public ?string $description = null;

    public ?string $phone = null;

    public ?string $address = null;

    public ?string $city = null;

    public ?string $province = null;

    public ?string $website = null;

    public $logo = null;

    public ?int $user_id = null;

    public bool $editing = false;

    public function mount(?Organization $organization = null): void
    {
        if ($organization->exists) {
            $this->organization = $organization;
            $this->editing = true;
            $this->name = $organization->name;
            $this->description = $organization->description;
            $this->phone = $organization->phone;
            $this->address = $organization->address;
            $this->city = $organization->city;
            $this->province = $organization->province;
            $this->website = $organization->website;
            $this->user_id = $organization->user_id;
        }
    }

    #[Computed]
    public function users()
    {
        return User::whereIn('role', ['rescuer', 'admin'])->orderBy('name')->get();
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
            'logo' => 'nullable|image|max:1024',
            'user_id' => $this->editing ? 'nullable|exists:users,id' : 'required|exists:users,id',
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

        if ($this->logo) {
            $path = $this->logo->store('organizations', 'public');
            $data['logo'] = Storage::url($path);

            if ($this->editing && $this->organization->logo) {
                $oldPath = str_replace(url('/storage'), '', $this->organization->logo);
                $oldPath = ltrim($oldPath, '/');
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
        }

        if ($this->editing) {
            $this->organization->update($data);
            Flux::toast(variant: 'success', text: __('Refugio actualizado.'));
        } else {
            $data['user_id'] = $this->user_id;
            $data['status'] = 'active';
            Organization::create($data);
            Flux::toast(variant: 'success', text: __('Refugio creado con éxito.'));
        }

        $this->redirect(route('admin.organizations.index'), navigate: true);
    }

    public function removeLogo(): void
    {
        if ($this->editing && $this->organization->logo) {
            $oldPath = str_replace(url('/storage'), '', $this->organization->logo);
            $oldPath = ltrim($oldPath, '/');
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            $this->organization->update(['logo' => null]);
            $this->logo = null;
            Flux::toast(text: __('Logo eliminado.'));
        }
    }

    public function render()
    {
        return view('livewire.admin.organization-form');
    }
}
