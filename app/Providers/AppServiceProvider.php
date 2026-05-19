<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

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
        View::composer('*', function ($view) {
            try {
                $menus = \App\Models\Category::with('subcategories')->get();
                $view->with('menus', $menus);
            } catch (\Throwable $e) {
                Log::error('ViewComposer error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
                $view->with('menus', collect());
            }
        });
    }
}
