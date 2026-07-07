<?php

namespace App\Livewire\Admin;

use App\Models\Organization;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Gestionar refugios')]
#[Layout('layouts.app')]
class OrganizationManager extends Component
{
    public function delete(Organization $organization): void
    {
        if ($organization->logo) {
            $oldPath = str_replace(url('/storage'), '', $organization->logo);
            $oldPath = ltrim($oldPath, '/');
            if (\Storage::disk('public')->exists($oldPath)) {
                \Storage::disk('public')->delete($oldPath);
            }
        }
        $organization->delete();
        \Flux\Flux::toast(variant: 'success', text: __('Refugio eliminado.'));
    }

    public function render()
    {
        return view('livewire.admin.organization-manager', [
            'organizations' => Organization::with('user')->latest()->get(),
        ]);
    }
}
