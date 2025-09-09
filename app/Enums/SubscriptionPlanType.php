<?php

namespace App\Enums;

enum SubscriptionPlanType: string
{
    case BASIC = 'basic';
    case PREMIUM = 'premium';
}

// This enum can be used throughout the application to ensure consistent subscription plan type management.
