<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class TransportService extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_plate_number',
        'vehicle_capacity',
        'vehicle_type',
        'driver_name',
        'driver_phone',
        'driver_license_number',
        'driver_monthly_salary',
        'route_name',
        'pickup_area',
        'dropoff_area',
        'monthly_fee',
        'status',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'vehicle_capacity' => 'integer',
            'driver_monthly_salary' => 'decimal:2',
            'monthly_fee' => 'decimal:2',
        ];
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(StudentTransport::class);
    }

    public function salaries(): MorphMany
    {
        return $this->morphMany(Salary::class, 'payable');
    }
}
