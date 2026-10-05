<?php

namespace App\Providers;

use App\Models\Profile;
use Illuminate\Support\Facades\View;
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
        // Link "About" di navigasi publik hanya muncul bila biodata sudah dibuat.
        View::composer('components.layouts.public', function ($view) {
            $view->with('hasAbout', Profile::query()->exists());
        });
    }
}
