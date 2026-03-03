<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\RateLimiter;
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
        //
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->response(function (Request $request, array $headers) {
                return response('API calling over limit...', 429, $headers);
            })->by($request->user()?->id ?: $request->ip());
        });

        Relation::enforceMorphMap([
            'activity' => \App\Models\ActivityAwardRegistration::class,
            'innovation' => \App\Models\InnovationAwardRegistration::class,
            'behavior' => \App\Models\BehaviorAwardRegistration::class,
            'user' => \App\Models\User::class,
        ]);

        \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));
        \Illuminate\Support\Facades\URL::forceScheme('https');
    }
}
