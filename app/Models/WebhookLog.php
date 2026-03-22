<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebhookLog extends BaseModel
{
    protected $fillable = [
        'webhook_id', 'event', 'payload', 'response_status',
        'response_body', 'success', 'duration_ms',
    ];

    protected function casts(): array
    {
        return [
            'payload'     => 'array',
            'success'     => 'boolean',
            'duration_ms' => 'integer',
        ];
    }

    public function webhook(): BelongsTo
    {
        return $this->belongsTo(Webhook::class);
    }
}
