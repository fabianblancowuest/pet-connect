<?php

namespace App\Livewire;

use App\Models\AdoptionRequest;
use App\Models\Favorite;
use App\Models\Pet;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Panel principal')]
#[Layout('layouts.app')]
class Dashboard extends Component
{
    #[Computed]
    public function stats(): array
    {
        $user = auth()->user();
        $stats = [];

        if (in_array($user->role, ['rescuer', 'admin'])) {
            $orgIds = $user->organizations()->pluck('id');
            $stats['availablePets'] = Pet::whereIn('organization_id', $orgIds)->where('status', 'available')->count();
            $stats['adoptedPets'] = Pet::whereIn('organization_id', $orgIds)->where('status', 'adopted')->count();
            $stats['pendingRequests'] = AdoptionRequest::whereIn('organization_id', $orgIds)->where('status', 'pending')->count();
        }

        $stats['myRequests'] = AdoptionRequest::where('user_id', $user->id)->count();
        $stats['myFavorites'] = Favorite::where('user_id', $user->id)->count();

        return $stats;
    }

    #[Computed]
    public function recentPets()
    {
        return Pet::with(['species', 'primaryImage'])
            ->where('status', 'available')
            ->latest()
            ->take(6)
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
