<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Pet extends Model
{
    const STATUS_AVAILABLE = 'available';
    const STATUS_ADOPTED = 'adopted';

    /** @use HasFactory<\Database\Factories\PetFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'species_id',
        'breed_id',
        'age_years',
        'age_months',
        'size',
        'color',
        'description',
        'status',
        'organization_id',
        'user_id',
        'is_vaccinated',
        'is_neutered',
        'is_house_trained',
        'good_with_kids',
        'good_with_pets',
    ];

    protected function casts(): array
    {
        return [
            'age_years' => 'integer',
            'age_months' => 'integer',
            'is_vaccinated' => 'boolean',
            'is_neutered' => 'boolean',
            'is_house_trained' => 'boolean',
            'good_with_kids' => 'boolean',
            'good_with_pets' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Pet $pet) {
            $pet->slug = $pet->slug ?? Str::slug($pet->name . '-' . Str::random(6));
        });
    }

    public function species(): BelongsTo
    {
        return $this->belongsTo(Species::class);
    }

    public function breed(): BelongsTo
    {
        return $this->belongsTo(Breed::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PetImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(PetImage::class)->where('is_primary', true);
    }

    public function adoptionRequests(): HasMany
    {
        return $this->hasMany(AdoptionRequest::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }
}
