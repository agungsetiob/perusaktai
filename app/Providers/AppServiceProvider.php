<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\ComplaintResponse;
use App\Models\User;

use App\Policies\ComplaintPolicy;
use App\Policies\ComplaintCategoryPolicy;
use App\Policies\ComplaintResponsePolicy;
use App\Policies\UserPolicy;

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
        Vite::prefetch(concurrency: 3);

        Gate::policy(
            Complaint::class,
            ComplaintPolicy::class
        );

        Gate::policy(
            ComplaintResponse::class,
            ComplaintResponsePolicy::class
        );

        Gate::policy(
            ComplaintCategory::class,
            ComplaintCategoryPolicy::class
        );

        Gate::policy(
            User::class,
            UserPolicy::class
        );
    }
}
