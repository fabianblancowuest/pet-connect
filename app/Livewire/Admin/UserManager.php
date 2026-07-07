<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Gestionar usuarios')]
#[Layout('layouts.app')]
class UserManager extends Component
{
    use WithPagination;

    public string $search = '';

    public ?string $roleFilter = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'roleFilter' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRoleFilter(): void
    {
        $this->resetPage();
    }

    public function delete(User $user): void
    {
        if ($user->id === auth()->id()) {
            Flux::toast(variant: 'error', text: __('No podés eliminar tu propio usuario.'));
            return;
        }

        $user->delete();
        Flux::toast(variant: 'success', text: __('Usuario eliminado.'));
    }

    public function render()
    {
        return view('livewire.admin.user-manager', [
            'users' => User::query()
                ->when($this->search, fn($q) => $q->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                }))
                ->when($this->roleFilter, fn($q) => $q->where('role', $this->roleFilter))
                ->latest()
                ->paginate(15),
        ]);
    }
}
