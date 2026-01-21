<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        if (config('app.env') === 'production' || env('APP_FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }

        Vite::prefetch(concurrency: 3);

        Inertia::share([
            'auth' => function () {
                if (Auth::check()) {
                    $user = Auth::user();
                    $user->loadCount('orders');

                    return [
                        'user' => [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'is_admin' => $user->is_admin,
                            'orders_count' => $user->orders_count ?? 0,
                        ],
                    ];
                }
                return ['user' => null];
            },
        ]);
    }
}
