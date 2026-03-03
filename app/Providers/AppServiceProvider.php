<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\GeneralModel;
use App\Models\AuthModel;
use App\Models\EzvizModel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('GeneralModel', function () {
            return new GeneralModel();
        });

        $this->app->bind('AuthModel', function () {
            return new AuthModel();
        });

        $this->app->bind('EzvizModel', function () {
            return new EzvizModel();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

