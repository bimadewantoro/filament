<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'overview',
        'is_free',
        'duration',
        'meta_description'
    ];

    private function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
