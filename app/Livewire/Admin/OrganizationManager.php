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
    public function toggleStatus(Organization $organization): void
    {
        $newStatus = match ($organization->status) {
            'active' => 'inactive',
            'inactive' => 'active',
            default => 'active',
        };

        $organization->update(['status' => $newStatus]);
        \Flux\Flux::toast(
            variant: 'success',
            text: $newStatus === 'active'
                ? __('Refugio activado.')
                : __('Refugio desactivado.'),
        );
    }

    public function delete(Organization $organization): void
    {
        if ($organization->logo) {
            $oldPath = ltrim(parse_url($organization->logo, PHP_URL_PATH) ?? '', '/');
            $oldPath = preg_replace('#^storage/#', '', $oldPath);
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
