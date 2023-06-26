<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider {

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();

        if (config('app.env') === 'production' || config('app.env') === 'staging') {
            \URL::forceScheme('https');
        }

        Blade::directive('number_format', function ($number) {
            return "<?php echo !empty($number) ? formatNumber($number) : ''; ?>";
        });

        Blade::directive('money_format', function ($amount) {
            return "<?php echo !empty($amount) && $amount > 0 ? formatNumber($amount) . ' €' : ''; ?>";
        });
        
        Blade::directive('money_format_2', function ($amount) {
            return "<?php echo !empty($amount) && $amount > 0 ? formatNumber($amount, 2) . ' €' : ''; ?>";
        });
    }

}
