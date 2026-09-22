<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_code',
        'name',
        'type',
        'business_id',
        'status',
        'google_review_url_override',
        'notes',
        'total_scans',
        'total_qr_scans',
        'total_nfc_scans',
        'activated_at',
        'last_scanned_at',
    ];

    protected function casts(): array
    {
        return [
            'total_scans' => 'integer',
            'total_qr_scans' => 'integer',
            'total_nfc_scans' => 'integer',
            'activated_at' => 'datetime',
            'last_scanned_at' => 'datetime',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function activations(): HasMany
    {
        return $this->hasMany(Activation::class)->orderBy('activated_at', 'desc');
    }

    public function scans(): HasMany
    {
        return $this->hasMany(Scan::class)->orderBy('scanned_at', 'desc');
    }

    public function isUnactivated(): bool
    {
        return $this->status === 'unactivated';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }

    public function getRedirectUrlAttribute(): string
    {
        return url('/r/' . $this->device_code);
    }

    public function getQrUrlAttribute(): string
    {
        return url('/r/' . $this->device_code . '?t=qr');
    }

    public function getNfcUrlAttribute(): string
    {
        return url('/r/' . $this->device_code . '?t=nfc');
    }

    public function getEffectiveReviewUrlAttribute(): ?string
    {
        if (!empty($this->google_review_url_override)) {
            return $this->google_review_url_override;
        }

        return $this->business?->google_review_url;
    }

    public function scopeUnactivated(Builder $query): Builder
    {
        return $query->where('status', 'unactivated');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', 'inactive');
    }

    public function scopeBlocked(Builder $query): Builder
    {
        return $query->where('status', 'blocked');
    }
}
