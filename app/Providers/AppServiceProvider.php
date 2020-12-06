<?php

namespace App\Providers;

use App\Models\PMResult;
use App\Models\Result;
use App\Observers\PMResultObserver;
use App\Observers\ResultObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Mockery\Generator\StringManipulation\Pass\Pass;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        URL::forceScheme('https');
        Result::observe(ResultObserver::class);
        PMResult::observe(PMResultObserver::class);
        Passport::hashClientSecrets();
    }
}
