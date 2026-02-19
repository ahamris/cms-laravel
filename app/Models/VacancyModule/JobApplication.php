<?php

namespace App\Models\VacancyModule;

use App\Observers\JobApplicationObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy(JobApplicationObserver::class)]
/**
 * @mixin IdeHelperJobApplication
 */
class JobApplication extends BaseModel
{
    protected $fillable = [
        'vacancy_id',
        'name',
        'email',
        'phone',
        'cover_letter',
        'resume_path',
        'linkedin_url',
        'portfolio_url',
        'repo_url',
        'status',
        'is_processed',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'is_processed' => 'boolean',
    ];

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }
}
