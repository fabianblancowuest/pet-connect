<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdoptionRequest extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    use SoftDeletes;

    protected $fillable = [
        'pet_id',
        'user_id',
        'organization_id',
        'status',
        'message',
        'phone',
        'housing_type',
        'has_outdoor_space',
        'has_other_pets',
        'other_pets_details',
        'previous_experience',
        'response',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'has_outdoor_space' => 'boolean',
            'has_other_pets' => 'boolean',
            'previous_experience' => 'boolean',
        ];
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
