<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view) {
            $settings = \Cache::remember('system_settings_global', 3600, function () {
                return \App\Models\SystemSetting::all()->pluck('value', 'key');
            });
            $view->with('app_settings', $settings);
        });
    }
}
