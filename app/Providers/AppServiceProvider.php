<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
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
        View::composer('layouts.app', function ($view) {
            $notifications = collect();
            $unreadCount = 0;

            if (Auth::check() && Auth::user()->role === 'anggota') {
                $notifications = Notification::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->latest()
                    ->take(5)
                    ->get();

                $unreadCount = $notifications->count();
            }

            $view->with(compact('notifications', 'unreadCount'));
        });
    }
}
