<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\CategoryComposer;
use App\View\Composers\SubscriptionComposer;
use App\View\Composers\SearchFilterComposer;
use App\View\Composers\CookieComposer;
use App\View\Composers\UserImpressionComposer;

class ViewServiceProvider extends ServiceProvider {

    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {
        View::composer(['fundus.products.create', 'fundus.products.edit', 'products.category', 'fundus.products.index', 'index'], CategoryComposer::class);
        View::composer(['products.category', 'index', 'users.show'], SubscriptionComposer::class);
        View::composer(['layouts.app'], SubscriptionComposer::class);
        View::composer(['products.category', 'fundus.products.index', 'index'], SearchFilterComposer::class);
        View::composer(['layouts.app'], CookieComposer::class);
        View::composer(['*'], UserImpressionComposer::class);
    }

}
