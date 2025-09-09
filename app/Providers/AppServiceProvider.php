<?php

namespace App\Providers;

use App\Models\Store;
use App\Policies\StorePolicy;
use Illuminate\Support\Facades\Gate; // <-- IMPORTANT: Import the Gate facade
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register your policy here
        Gate::policy(Store::class, StorePolicy::class);
    }
}