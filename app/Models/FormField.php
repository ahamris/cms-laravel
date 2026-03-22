<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormField extends BaseModel
{
    protected $fillable = [
        'form_id',
        'name',
        'label',
        'type',
        'placeholder',
        'help_text',
        'is_required',
        'validation_rules',
        'options',
        'default_value',
        'sort_order',
        'width',
        'conditional_on',
        'crm_map_to',
    ];

    protected function casts(): array
    {
        return [
            'is_required'      => 'boolean',
            'validation_rules' => 'array',
            'options'          => 'array',
            'conditional_on'   => 'array',
            'sort_order'       => 'integer',
        ];
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public static function supportedTypes(): array
    {
        return [
            'text', 'email', 'phone', 'textarea', 'number', 'url',
            'select', 'radio', 'checkbox', 'checkbox_group',
            'date', 'file', 'hidden', 'heading', 'divider', 'consent',
        ];
    }
}
