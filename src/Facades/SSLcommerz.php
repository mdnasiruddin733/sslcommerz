<?php

namespace Webpane\SSLcommerz\Facades;
use Illuminate\Support\Facades\Facade;

class SSLcommerz extends Facade{
    protected static function getFacadeAccessor(){
        return "sslcommerz";
    }
}
