<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'invoice_number',
        'sold_at',
        'subtotal',
        'discount_amount',
        'total_amount',
        'paid_amount',
        'balance_amount',
        'status',
        'recorded_by',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'sold_at' => 'date',
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'balance_amount' => 'decimal:2',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function lines(): HasMany
    {
        return $this->hasMany(StudentSaleItem::class);
    }
}
