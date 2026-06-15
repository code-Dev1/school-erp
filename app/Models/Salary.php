<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'payable_type',
        'payable_id',
        'month',
        'year',
        'base_salary',
        'bonus',
        'deduction',
        'net_salary',
        'paid_at',
        'status',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'month' => 'integer',
            'year' => 'integer',
            'base_salary' => 'decimal:2',
            'bonus' => 'decimal:2',
            'deduction' => 'decimal:2',
            'net_salary' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Salary $salary): void {
            $salary->net_salary = (float) $salary->base_salary + (float) $salary->bonus - (float) $salary->deduction;
        });
    }

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }
}
