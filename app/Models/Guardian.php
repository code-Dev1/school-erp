<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Guardian extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'father_name',
        'first_name',
        'last_name',
        'occupation',
        'job',
        'contact_number',
        'phone',
        'whatsapp_number',
        'email',
        'province',
        'district',
        'village',
        'address',
        'status',
        'note',
        'tazkira_number',
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_guardian')
            ->withPivot(['is_primary', 'relationship'])
            ->withTimestamps();
    }

    public function announcementRecipients(): MorphMany
    {
        return $this->morphMany(AnnouncementRecipient::class, 'recipient');
    }
}
