<?php

namespace App\Models;

use App\Enums\ItemStockAvailability;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $primaryKey = 'itemID';

    protected $fillable = [
        'store_id',     // Foreign key to the store
        'barcode',      // Unique barcode for the item
        'itemName',     // Name of the item
        'itemType',     // Type/category of the item
        'sellingPrice',     // Selling price of the item
        'stockAvailability',    // Stock availability status
    ];

    protected $casts = [    // Cast enum field
        'stockAvailability' => ItemStockAvailability::class,
    ];

    // An item belongs to one store
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
