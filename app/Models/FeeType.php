<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeeType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function feeStructures(): HasMany
    {
        return $this->hasMany(FeeStructure::class);
    }
}
