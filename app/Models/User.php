<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole; // Import your enum
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'userID';

    protected $fillable = [
        'userName',     // User's full name
        'email',    // User's email address
        'password', // Hashed password
        'role',    // User's role (admin, consumer, store owner)
        'nationalIDNumber', // User's national ID number
        'verificationDocumentsPath', // Path to verification documents
        'location', // User's location
        'preferences', // User's preferences
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'preferences' => 'array',
        'role' => UserRole::class,
    ];

    // If the user is a store owner, they have one store
    public function store()
    {
        return $this->hasOne(Store::class, 'user_id', 'userID');
    }

    // If the user is a consumer, they can write many reviews
    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id', 'userID');
    }

    // If the user is a consumer, they can follow many stores
    public function followedStores()
    {
        return $this->belongsToMany(Store::class, 'consumer_store_follow', 'user_id', 'store_id');
    }
}
