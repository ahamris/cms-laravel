<?php

namespace App\Models\VacancyModule;

use App\Models\BaseModel;
use Database\Factories\VacancyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperVacancy
 */
class Vacancy extends BaseModel
{
    use HasFactory;

    protected static function newFactory()
    {
        return VacancyFactory::new();
    }

    protected $fillable = [
        'title',
        'slug',
        'location',
        'short_code',
        'type',
        'hours_per_week',
        'experience_level',
        'department',
        'category',
        'description',
        'requirements',
        'responsibilities',
        'salary_range',
        'is_active',
        'is_processed',
        'repo_url',
        'closing_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_processed' => 'boolean',
        'closing_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vacancy) {
            if (empty($vacancy->slug)) {
                $vacancy->slug = Str::slug($vacancy->title);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('closing_date')
                    ->orWhere('closing_date', '>=', now());
            });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }
}
