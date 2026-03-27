<?php

namespace App\Models;

/**
 * @mixin IdeHelperAiTask
 */
class AiTask extends BaseModel
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_RUNNING = 'running';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'user_id',
        'task_type',
        'status',
        'payload',
        'result',
        'provider',
        'model',
        'duration_ms',
        'error_message',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'result' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
}
