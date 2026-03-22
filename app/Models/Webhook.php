<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Webhook extends BaseModel
{
    protected $fillable = [
        'name', 'url', 'events', 'secret', 'is_active',
        'failure_count', 'last_triggered_at',
    ];

    protected function casts(): array
    {
        return [
            'events'            => 'array',
            'is_active'         => 'boolean',
            'failure_count'     => 'integer',
            'last_triggered_at' => 'datetime',
        ];
    }

    public function logs(): HasMany
    {
        return $this->hasMany(WebhookLog::class);
    }

    public function shouldFireFor(string $event): bool
    {
        return $this->is_active && in_array($event, $this->events ?? []);
    }
}
