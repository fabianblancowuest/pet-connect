<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Usuario')]
#[Layout('layouts.app')]
class UserForm extends Component
{
    public ?User $user = null;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $role = 'adopter';

    public bool $editing = false;

    public function mount(?User $user = null): void
    {
        if ($user && $user->exists) {
            $this->user = $user;
            $this->editing = true;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->role;
        }
    }

    protected function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                $this->editing
                    ? Rule::unique('users', 'email')->ignore($this->user->id)
                    : Rule::unique('users', 'email'),
            ],
            'role' => 'required|in:adopter,rescuer,admin',
        ];

        if (!$this->editing) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        return $rules;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if ($this->editing) {
            if ($this->password) {
                $this->validate(['password' => 'string|min:8|confirmed']);
                $data['password'] = Hash::make($this->password);
            }
            $this->user->update($data);
            Flux::toast(variant: 'success', text: __('Usuario actualizado.'));
        } else {
            $data['password'] = Hash::make($this->password);
            User::create($data);
            Flux::toast(variant: 'success', text: __('Usuario creado con éxito.'));
        }

        $this->redirect(route('admin.users.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.user-form');
    }
}
