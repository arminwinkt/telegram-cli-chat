<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $madelinePath = base_path() . DIRECTORY_SEPARATOR . 'bootstrap' . DIRECTORY_SEPARATOR . 'madeline.php';

        if (!file_exists($madelinePath)) {
            copy('https://phar.madelineproto.xyz/madeline.php', $madelinePath);
        }

        define('MADELINE_BRANCH', '');
        require_once $madelinePath;
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
