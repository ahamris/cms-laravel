<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperFormBuilder
 */
class FormBuilder extends BaseModel
{
    use Sluggable;

    protected $fillable = [
        'title',
        'description',
        'identifier',
        'slug',
        'fields',
        'settings',
        'success_message',
        'redirect_url',
        'send_email_notification',
        'notification_emails',
        'submit_button_text',
        'is_active',
        'is_api_form',
        'api_url',
        'api_token',
        'sort_order',
    ];

    protected $casts = [
        'fields' => 'array',
        'settings' => 'array',
        'send_email_notification' => 'boolean',
        'is_active' => 'boolean',
        'is_api_form' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /**
     * Relationship: Form submissions
     */
    public function submissions()
    {
        return $this->hasMany(FormSubmission::class);
    }

    /**
     * Scope: Active forms
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Ordered
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    /**
     * Scope: API forms
     */
    public function scopeApiForms($query)
    {
        return $query->where('is_api_form', true);
    }

    /**
     * Get form by identifier
     */
    public static function getByIdentifier($identifier)
    {
        return Cache::remember("form_builder_{$identifier}", 3600, function () use ($identifier) {
            return static::where('identifier', $identifier)
                ->where('is_active', true)
                ->first();
        });
    }

    /**
     * Get all cached forms
     */
    public static function getCached()
    {
        return Cache::remember('form_builders_all', 3600, function () {
            return static::active()->ordered()->get();
        });
    }

    /**
     * Clear cache
     */
    public static function clearCache()
    {
        Cache::forget('form_builders_all');
        // Clear individual form caches
        static::all()->each(function ($form) {
            Cache::forget("form_builder_{$form->identifier}");
        });
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            static::clearCache();
        });

        static::deleted(function () {
            static::clearCache();
        });
    }

    /**
     * Get unread submissions count
     */
    public function getUnreadSubmissionsCountAttribute()
    {
        return $this->submissions()->where('is_read', false)->count();
    }

    /**
     * Get total submissions count
     */
    public function getTotalSubmissionsCountAttribute()
    {
        return $this->submissions()->count();
    }

    /**
     * Check if form is an API form
     */
    public function isApiForm(): bool
    {
        return $this->is_api_form === true;
    }
}

