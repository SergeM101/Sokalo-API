<?php

namespace App\Models;

use App\Enums\SubscriptionPlanType;
use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $primaryKey = 'subscriptionID';

    protected $fillable = [
        'store_id',     // Foreign key to the store
        'planType',     // Type of subscription plan
        'subStatus',    // Current status of the subscription
        'startDate',    // Start date of the subscription
        'endDate'       // End date of the subscription
    ];

    protected $casts = [    // Cast dates to Carbon instances
        'startDate' => 'datetime',
        'endDate' => 'datetime',
        'planType' => SubscriptionPlanType::class,
        'subStatus' => SubscriptionStatus::class,
    ];

    // A subscription belongs to a store
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    // A subscription can have many payments (renewals)
    public function payments()
    {
        return $this->hasMany(Payment::class, 'subscription_id', 'subscriptionID');
    }
}
