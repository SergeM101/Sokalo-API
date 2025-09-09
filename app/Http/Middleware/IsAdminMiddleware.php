<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the authenticated user has the 'admin' role
        if ($request->user() && $request->user()->role === UserRole::ADMIN) {
            return $next($request); // User is an admin, proceed with the request
        }

        // If not, return a 'Forbidden' error
        return response()->json(['message' => 'Access denied.'], 403);
    }
}
