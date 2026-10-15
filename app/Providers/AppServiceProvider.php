<?php

namespace App\Providers;

use App\Models\Peserta;
use App\Models\Event;
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
        // View Composer khusus untuk sidebar admin
        View::composer('admin.partials.sidebar', function ($view) {
            // 1. Dapatkan event ID dari route parameter (?event) atau dari Session aktif
            $eventId = request()->route('event') ?? session('active_event_id');

            // Jika route menyimpan model Event langsung (route model binding)
            if ($eventId instanceof Event) {
                $eventId = $eventId->id;
            }

            // 2. Fallback jika tidak ada event_id di route/session
            if (!$eventId) {
                $eventId = Event::latest('id')->value('id');
            }

            // 3. Hitung statistik peserta khusus untuk event ID ini
            $menungguReview = $eventId 
                ? Peserta::where('event_id', $eventId)->where('status', 'diterima')->count() 
                : 0;

            // 4. Otomatis ketersediaan variabel $menungguReview di seluruh halaman yang memuat sidebar
            $view->with('menungguReview', $menungguReview);
        });
    }
}
