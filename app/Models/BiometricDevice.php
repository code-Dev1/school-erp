<?php

namespace App\Models;

use App\Enums\Biometric\BiometricDeviceStatus;
use App\Enums\Biometric\BiometricDeviceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BiometricDevice extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'ip_address', 'port', 'location', 'device_type', 'status'];

    protected function casts(): array
    {
        return [
            'port' => 'integer',
            'device_type' => BiometricDeviceType::class,
            'status' => BiometricDeviceStatus::class,
        ];
    }

    public function logs(): HasMany
    {
        return $this->hasMany(BiometricLog::class, 'device_id');
    }
}
