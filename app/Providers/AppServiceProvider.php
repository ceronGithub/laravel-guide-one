<?php

namespace App\Providers;

use App\Classes\Constants\PaymentOptions;
use App\Classes\Registries\ValidatorRegistry;
use App\Classes\TransactionValidators\PaynamicsValidator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(ValidatorRegistry::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        $this->app->make(ValidatorRegistry::class)
        ->register(PaymentOptions::PAYNAMICS, new PaynamicsValidator);
    }
}
