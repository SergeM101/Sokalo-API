<?php

namespace App\Models;

use App\Enums\StoreCategory;
use App\Enums\StoreVerificationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $primaryKey = 'storeID';

    protected $fillable = [
        'user_id',  // Foreign key to the user (store owner)
        'officialName',     // Official name of the store
        'address',     // Store address
        'category',     // Enum field
        'verificationStatus',   // Enum field
        'contactEmail',
        'contactPhone', // Contact information
        'images'
    ];

    protected $casts = [    // Cast enum and array fields
        'images' => 'array',
        'category' => StoreCategory::class,
        'verificationStatus' => StoreVerificationStatus::class,
    ];

    // A store belongs to one user (the owner)
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // A store has many public items
    public function items()
    {
        return $this->hasMany(Item::class, 'store_id', 'storeID');
    }

    // A store has many reviews
    public function reviews()
    {
        return $this->hasMany(Review::class, 'store_id', 'storeID');
    }

    // A store has one subscription
    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'store_id', 'storeID');
    }

    // A store can have many followers (consumers)
    public function followers()
    {
        return $this->belongsToMany(User::class, 'consumer_store_follow', 'store_id', 'user_id');
    }

    /**
     * A store can have many promotions.
     */
    public function promotions()
    {
        return $this->hasMany(Promotion::class, 'store_id', 'storeID');
    }
};
