<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TransportRoute extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_transport', 'route_id', 'student_id')
            ->withPivot(['vehicle_id', 'fee_amount', 'starts_at', 'ends_at'])
            ->withTimestamps();
    }
}
