<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormSubmission extends BaseModel
{
    protected $fillable = [
        'form_id',
        'data',
        'files',
        'ip_address',
        'user_agent',
        'referrer_url',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'status',
        'lead_score',
        'converted_contact_id',
        'converted_deal_id',
        'admin_notes',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'data'         => 'array',
            'files'        => 'array',
            'lead_score'   => 'integer',
            'processed_at' => 'datetime',
        ];
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function convertedContact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'converted_contact_id');
    }

    public function convertedDeal(): BelongsTo
    {
        return $this->belongsTo(CrmDeal::class, 'converted_deal_id');
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeProcessed($query)
    {
        return $query->where('status', 'processed');
    }

    public function getFieldValue(string $fieldName): mixed
    {
        return $this->data[$fieldName] ?? null;
    }
}
