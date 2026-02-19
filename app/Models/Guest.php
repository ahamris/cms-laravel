<?php

namespace App\Models;

/**
 * @mixin IdeHelperGuest
 */
class Guest extends BaseModel
{
    protected $connection = 'sqlite';

    protected $table = 'guests';

    protected $fillable = [
        'ip_address',
        'last_activity'
    ];

    protected function casts(): array
    {
        return [
            'last_activity' => 'datetime'
        ];
    }
}
