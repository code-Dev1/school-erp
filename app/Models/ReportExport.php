<?php

namespace App\Models;

use App\Enums\Reports\ReportType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportExport extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'title', 'filters', 'file_path', 'generated_by', 'generated_at'];

    protected function casts(): array
    {
        return [
            'type' => ReportType::class,
            'filters' => 'array',
            'generated_at' => 'datetime',
        ];
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
