<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'paymentID';

    protected $fillable = [
        'user_id',  // Foreign key to the user
        'subscription_id',  // Foreign key to the subscription
        'amount',  // Payment amount
        'paymentMethod',  // Payment method (e.g., credit card, PayPal)
        'transactionReference',  // Reference for the transaction
        'payStatus',  // Payment status (e.g., completed, pending)
        'payedTime',  // Time when the payment was made
        'attribute'   // Additional attributes (if any)
    ];

    protected $casts = [
        'payedTime' => 'datetime',
        'paymentMethod' => PaymentMethod::class,
        'payStatus' => PaymentStatus::class,
    ];

    // A payment belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // A payment may belong to a subscription
    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
}
