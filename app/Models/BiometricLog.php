<?php

namespace App\Models;

use App\Enums\Biometric\BiometricLogType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BiometricLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'biometric_uid',
        'person_id',
        'person_type',
        'device_id',
        'timestamp',
        'check_time',
        'log_type',
        'check_type',
        'raw_data',
        'synced_at',
    ];

    protected function casts(): array
    {
        return [
            'biometric_uid' => 'integer',
            'timestamp' => 'datetime',
            'check_time' => 'datetime',
            'raw_data' => 'array',
            'synced_at' => 'datetime',
            'log_type' => BiometricLogType::class,
        ];
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(BiometricDevice::class, 'device_id');
    }

    public function person(): MorphTo
    {
        return $this->morphTo();
    }
}
