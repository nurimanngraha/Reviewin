<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'category',
        'address',
        'phone',
        'email',
        'google_review_url',
        'google_place_id',
        'logo',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function activations(): HasMany
    {
        return $this->hasMany(Activation::class);
    }

    public function scans(): HasMany
    {
        return $this->hasMany(Scan::class);
    }

    /**
     * Get active devices count
     */
    public function getActiveDevicesCountAttribute(): int
    {
        return $this->devices()->where('status', 'active')->count();
    }

    /**
     * Get Google review URL, falling back to constructing from Place ID
     */
    public function getReviewUrlAttribute(): ?string
    {
        if (!empty($this->google_review_url)) {
            return $this->google_review_url;
        }

        if (!empty($this->google_place_id)) {
            return 'https://search.google.com/local/writereview?placeid=' . urlencode($this->google_place_id);
        }

        return null;
    }
}
