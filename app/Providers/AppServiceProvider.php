<?php

namespace App\Providers;

use App\Models\Peserta;
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
        \Illuminate\Support\Facades\View::composer('admin.partials.sidebar', function ($view) {
            $view->with(
                'menungguReview',
                \App\Models\Peserta::where('status', 'menunggu_review')->count()
            );
        });
    }
}
