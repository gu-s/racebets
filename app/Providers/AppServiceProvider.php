<?php

namespace App\Providers;

use App\Repositories\BalanceRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CustomerRepository;

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

        $this->app->bind(CustomerRepository::class);
        $this->app->bind(BalanceRepository::class);

    }
}
