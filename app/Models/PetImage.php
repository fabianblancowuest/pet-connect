<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PetImage extends Model
{
    /** @use HasFactory<\Database\Factories\PetImageFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        parent::booted();

        static::deleting(function (PetImage $image) {
            $relativePath = ltrim(parse_url($image->image_path, PHP_URL_PATH) ?? '', '/');
            $relativePath = preg_replace('#^storage/#', '', $relativePath);
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($relativePath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($relativePath);
            }
        });
    }

    protected $fillable = [
        'pet_id',
        'image_path',
        'is_primary',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }
}
