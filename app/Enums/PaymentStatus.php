<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
}

// This enum can be used throughout the application to ensure consistent payment status management.
// Example usage:
// $status = PaymentStatus::COMPLETED;