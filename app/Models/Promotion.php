<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $primaryKey = 'promoID';

    protected $fillable = [
        'store_id',     // Foreign key to the store
        'title',        // Promotion title
        'description',  // Promotion description
        'startDate',    // Promotion start date
        'endDate'       // Promotion end date
    ];

    protected $casts = [    // Cast dates to Carbon instances
        'startDate' => 'datetime',
        'endDate' => 'datetime',
    ];

    // A promotion belongs to a store
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
