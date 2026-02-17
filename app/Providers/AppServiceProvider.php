<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\CurrencyHelper;
use Illuminate\Support\Facades\Blade;

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
        // Make CurrencyHelper available in all Blade views
        Blade::directive('currency', function ($price) {
            return "<?php echo \App\Helpers\CurrencyHelper::formatPrice({$price}); ?>";
        });

        // Make currency symbol available
        Blade::directive('currencySymbol', function () {
            return "<?php echo \App\Helpers\CurrencyHelper::getSymbol(); ?>";
        });

        // Make converted price available
        Blade::directive('convertPrice', function ($price) {
            return "<?php echo number_format(\App\Helpers\CurrencyHelper::convertPrice({$price}), 2); ?>";
        });
    }
}
