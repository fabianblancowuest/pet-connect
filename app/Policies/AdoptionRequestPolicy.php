<?php

namespace App\Policies;

use App\Models\AdoptionRequest;
use App\Models\User;

class AdoptionRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->organizations()->exists();
    }

    public function view(User $user, AdoptionRequest $adoptionRequest): bool
    {
        return $user->organizations()->pluck('id')->contains($adoptionRequest->organization_id);
    }

    public function approve(User $user, AdoptionRequest $adoptionRequest): bool
    {
        return $user->organizations()->pluck('id')->contains($adoptionRequest->organization_id);
    }

    public function reject(User $user, AdoptionRequest $adoptionRequest): bool
    {
        return $user->organizations()->pluck('id')->contains($adoptionRequest->organization_id);
    }

    public function updateNotes(User $user, AdoptionRequest $adoptionRequest): bool
    {
        return $user->organizations()->pluck('id')->contains($adoptionRequest->organization_id);
    }
}
