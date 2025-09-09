<?php

namespace App\Enums;

enum ItemStockAvailability: string
{
    case IN_STOCK = 'in_stock';
    case OUT_OF_STOCK = 'out_of_stock';
    case LOW_STOCK = 'low_stock';
}

// This enum can be used throughout the application to ensure consistent item stock availability management.
// Example usage:
// $item->stock_availability = ItemStockAvailability::IN_STOCK;