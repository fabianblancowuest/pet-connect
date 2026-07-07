<?php

namespace App\Policies;

use App\Models\Pet;
use App\Models\User;

class PetPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->organizations()->exists();
    }

    public function view(User $user, Pet $pet): bool
    {
        return $user->organizations()->pluck('id')->contains($pet->organization_id);
    }

    public function create(User $user): bool
    {
        return $user->organizations()->exists();
    }

    public function update(User $user, Pet $pet): bool
    {
        return $user->organizations()->pluck('id')->contains($pet->organization_id);
    }

    public function delete(User $user, Pet $pet): bool
    {
        return $user->organizations()->pluck('id')->contains($pet->organization_id);
    }
}
