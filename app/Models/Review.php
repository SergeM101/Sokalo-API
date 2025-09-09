<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $primaryKey = 'reviewID';

    protected $fillable = [
        'user_id',   // Foreign key to the user (author)
        'store_id',     // Foreign key to the store
        'rating',     // Rating given by the user
        'comment'    // Review comment 
    ];

    // A review belongs to a user (the author)
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // A review belongs to a store
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
