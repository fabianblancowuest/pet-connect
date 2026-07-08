<?php

namespace App\Livewire\Admin;

use App\Models\Developer;
use Flux\Flux;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Desarrollador')]
#[Layout('layouts.app')]
class DeveloperForm extends Component
{
    use WithFileUploads;

    public ?Developer $developer = null;

    public string $name = '';

    public ?string $role = null;

    public ?string $description = null;

    public $image = null;

    public bool $editing = false;

    public function mount(?Developer $developer = null): void
    {
        if ($developer && $developer->exists) {
            $this->developer = $developer;
            $this->editing = true;
            $this->name = $developer->name;
            $this->role = $developer->role;
            $this->description = $developer->description;
        }
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:5000',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'role' => $this->role,
            'description' => $this->description,
        ];

        if ($this->image) {
            $path = $this->image->store('developers', 'public');
            $data['image'] = Storage::url($path);

            if ($this->editing && $this->developer->image) {
                $oldPath = ltrim(parse_url($this->developer->image, PHP_URL_PATH) ?? '', '/');
                $oldPath = preg_replace('#^storage/#', '', $oldPath);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
        }

        if ($this->editing) {
            $this->developer->update($data);
            Flux::toast(variant: 'success', text: __('Desarrollador actualizado.'));
        } else {
            $maxSort = Developer::max('sort_order') ?? 0;
            $data['sort_order'] = $maxSort + 1;
            Developer::create($data);
            Flux::toast(variant: 'success', text: __('Desarrollador creado con éxito.'));
        }

        $this->redirect(route('admin.developers.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.developer-form');
    }
}
