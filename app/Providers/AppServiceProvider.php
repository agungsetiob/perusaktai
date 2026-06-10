<?php

namespace App\Providers;

use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\ComplaintResponse;
use App\Models\User;
use App\Models\Room;
use App\Policies\RoomPolicy;
use App\Policies\ComplaintPolicy;
use App\Policies\ComplaintCategoryPolicy;
use App\Policies\ComplaintResponsePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\Gate;

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

        Gate::policy(
            Room::class,
            RoomPolicy::class
        );

        if (App::environment('local')) {
            // Paksa semua HttpClient bawaan Laravel & Package untuk mengabaikan SSL peer verification
            Http::globalOptions([
                'verify' => false,
            ]);
        }
    }
}
