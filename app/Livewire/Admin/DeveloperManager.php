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
            $oldPath = ltrim(parse_url($developer->image, PHP_URL_PATH) ?? '', '/');
            $oldPath = preg_replace('#^storage/#', '', $oldPath);
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
