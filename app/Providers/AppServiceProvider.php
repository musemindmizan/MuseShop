<?php

namespace App\Providers;

use App\Models\ContactMessage;
use App\Services\CartService;
use App\Services\WishlistService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
        \App\Contracts\ImageUploaderInterface::class,
        \App\Services\LocalImageUploader::class
    );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('cartCount', app(CartService::class)->count());
            $view->with('wishlistProductIds', app(WishlistService::class)->productIds());
        });

        View::composer('layouts.admin', function ($view) {
            $view->with('unreadMessagesCount', ContactMessage::whereNull('read_at')->count());
        });
    }
}
