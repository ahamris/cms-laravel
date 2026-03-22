<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmTicketReply extends BaseModel
{
    protected $table = 'crm_ticket_replies';

    protected $fillable = [
        'ticket_id', 'user_id', 'direction', 'body', 'is_ai_generated',
    ];

    protected function casts(): array
    {
        return [
            'is_ai_generated' => 'boolean',
        ];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(CrmTicket::class, 'ticket_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
