<?php

namespace App\Models;

use App\Enums\Biometric\BiometricLogType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BiometricLog extends Model
{
    use HasFactory;

    protected $fillable = ['biometric_uid', 'device_id', 'timestamp', 'log_type', 'synced_at'];

    protected function casts(): array
    {
        return [
            'biometric_uid' => 'integer',
            'timestamp' => 'datetime',
            'synced_at' => 'datetime',
            'log_type' => BiometricLogType::class,
        ];
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(BiometricDevice::class, 'device_id');
    }
}
