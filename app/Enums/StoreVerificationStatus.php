<?php

namespace App\Enums;

enum StoreVerificationStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}

// This enum can be used throughout the application to ensure consistent store verification status management.