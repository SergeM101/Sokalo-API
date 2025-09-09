<?php
// app/Enums/UserRole.php
namespace App\Enums;

enum UserRole: string
{
    case CONSUMER = 'consumer';
    case STORE_OWNER = 'store_owner';
    case ADMIN = 'admin';
}
// This enum can be used throughout the application to ensure consistent role management.