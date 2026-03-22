<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends BaseModel
{
    use Sluggable;

    protected $fillable = [
        'name', 'slug', 'description', 'type',
        'success_message', 'redirect_url',
        'notification_emails', 'notification_slack',
        'honeypot_field', 'recaptcha_enabled',
        'crm_pipeline', 'crm_auto_contact', 'crm_auto_deal', 'crm_deal_value',
        'max_submissions', 'opens_at', 'closes_at',
        'is_active', 'styling',
    ];

    protected function casts(): array
    {
        return [
            'recaptcha_enabled' => 'boolean',
            'crm_auto_contact'  => 'boolean',
            'crm_auto_deal'     => 'boolean',
            'is_active'         => 'boolean',
            'styling'           => 'array',
            'opens_at'          => 'datetime',
            'closes_at'         => 'datetime',
        ];
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source'    => 'name',
                'maxLength' => 200,
                'separator' => '-',
            ],
        ];
    }

    public function fields(): HasMany
    {
        return $this->hasMany(FormField::class)->orderBy('sort_order');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }

    public function isAcceptingSubmissions(): bool
    {
        if (!$this->is_active) return false;
        if ($this->opens_at && now()->lt($this->opens_at)) return false;
        if ($this->closes_at && now()->gt($this->closes_at)) return false;
        if ($this->max_submissions && $this->submissions()->count() >= $this->max_submissions) return false;
        return true;
    }

    public function getValidationRules(): array
    {
        $rules = [];
        foreach ($this->fields as $field) {
            if (in_array($field->type, ['heading', 'divider'])) {
                continue;
            }

            $fieldRules = [];
            if ($field->is_required) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            match ($field->type) {
                'email'    => $fieldRules[] = 'email',
                'url'      => $fieldRules[] = 'url',
                'number'   => $fieldRules[] = 'numeric',
                'phone'    => $fieldRules[] = 'regex:/^[\+]?[\d\s\-\(\)]+$/',
                'file'     => $fieldRules[] = 'file|max:' . (($field->options['max_size_mb'] ?? 10) * 1024),
                'date'     => $fieldRules[] = 'date',
                'select', 'radio' => $fieldRules[] = 'in:' . implode(',', $field->options['choices'] ?? []),
                default    => null,
            };

            if ($field->validation_rules) {
                $fieldRules = array_merge($fieldRules, $field->validation_rules);
            }

            $rules["fields.{$field->name}"] = $fieldRules;
        }
        return $rules;
    }
}
