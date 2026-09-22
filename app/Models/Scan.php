<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Scan extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'business_id',
        'scan_type',
        'ip_address',
        'user_agent',
        'device_type',
        'platform',
        'browser',
        'scanned_at',
    ];

    protected function casts(): array
    {
        return [
            'scanned_at' => 'datetime',
        ];
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function scopeQr(Builder $query): Builder
    {
        return $query->where('scan_type', 'qr');
    }

    public function scopeNfc(Builder $query): Builder
    {
        return $query->where('scan_type', 'nfc');
    }
}
