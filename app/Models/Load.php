<?php

namespace App\Models;

use App\Enums\AcademicYear;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'program_id',
    'subject_id',
    'academic_year',
    'term',
    'user_id',
    'grading_sheet',
    'submission_deadline',
])]
class Load extends Model
{
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getSubmissionStatusAttribute(): string
    {
        return $this->grading_sheet ? 'submitted' : 'pending';
    }

    protected function casts(): array
    {
        return [
            'academic_year' => AcademicYear::class,
            'submission_deadline' => 'datetime',
        ];
    }
}
