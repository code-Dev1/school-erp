<?php

namespace App\Models;

use App\Enums\Transport\VehicleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['plate_number', 'driver_name', 'driver_contact', 'capacity', 'status'];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'status' => VehicleStatus::class,
        ];
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_transport')
            ->withPivot(['route_id', 'fee_amount', 'starts_at', 'ends_at'])
            ->withTimestamps();
    }
}
