<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

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
        // Fix for older MySQL versions
        Schema::defaultStringLength(191);

        // Share the current locale with all views
        View::composer('*', function ($view) {
            $view->with('currentLocale', App::getLocale());
        });

        // Register translation helper
        $this->app->singleton('translator', function ($app) {
            $loader = $app->make('translation.loader');
            $locale = $app->getLocale();

            $trans = new \Illuminate\Translation\Translator($loader, $locale);
            $trans->setFallback($app->getFallbackLocale());

            return $trans;
        });
    }
}
