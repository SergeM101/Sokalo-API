<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CAMPAY = 'campay';
    case MTN_MOBILE_MONEY = 'mtn-mobile-money';
    case ORANGE_MONEY = 'orange-money';
}

// This enum can be used throughout the application to ensure consistent payment method management.
// Example usage:
// $method = PaymentMethod::CREDIT_CARD;