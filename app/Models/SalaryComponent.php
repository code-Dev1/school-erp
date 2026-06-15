<?php

namespace App\Models;

use App\Enums\Finance\SalaryComponentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryComponent extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'type', 'title', 'amount'];

    protected function casts(): array
    {
        return [
            'type' => SalaryComponentType::class,
            'amount' => 'decimal:2',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
