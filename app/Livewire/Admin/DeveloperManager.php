<?php

namespace App\Livewire\Admin;

use App\Models\Developer;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Gestionar desarrolladores')]
#[Layout('layouts.app')]
class DeveloperManager extends Component
{
    public function delete(Developer $developer): void
    {
        if ($developer->image) {
            $oldPath = str_replace(url('/storage'), '', $developer->image);
            $oldPath = ltrim($oldPath, '/');
            if (\Storage::disk('public')->exists($oldPath)) {
                \Storage::disk('public')->delete($oldPath);
            }
        }
        $developer->delete();
        \Flux\Flux::toast(variant: 'success', text: __('Desarrollador eliminado.'));
    }

    public function render()
    {
        return view('livewire.admin.developer-manager', [
            'developers' => Developer::orderBy('sort_order')->get(),
        ]);
    }
}
