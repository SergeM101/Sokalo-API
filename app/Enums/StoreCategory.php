<?php

namespace App\Enums;

enum StoreCategory: string
{
    case SUPERMARKET = 'supermarket';
    case ELECTRONICS = 'electronics';
    case CLOTHING = 'clothing';
}
// This enum can be used throughout the application to ensure consistent store category management.