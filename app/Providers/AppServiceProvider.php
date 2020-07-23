<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        Schema::defaultStringLength(191);

        //DB::listen(function($query) {
        //    File::append(
        //        storage_path('/logs/query.log'),
        //        '[' . date('Y-m-d H:i:s') . '] ' . $query->sql . PHP_EOL
        //    );
        //});
    }
}
