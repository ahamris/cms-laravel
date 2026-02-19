<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperSubscriptionTrial
 */
class SubscriptionTrial extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'user_id',
        'start_date',
        'end_date',
        'status',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
