<?php

namespace Webpane\SSLcommerz;

use Illuminate\Support\ServiceProvider;

class SSLcommerzServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        
        $this->mergeConfigFrom(__DIR__."/../config/sslcommerz.php","sslcommerz");
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
      
       $this->publishes([
           __DIR__."/../config/sslcommerz.php"=>config_path("sslcommerz.php"),
       ]);

       $this->app->bind("sslcommerz",function(){
           return new SSLcommerz();
       });
    }
}
