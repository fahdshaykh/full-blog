<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CounterFacade extends Facade {
    
    /**
     * A Facade do contract
     * @method static int increment(string $key, array $tags = null)
    */
    public static function getFacadeAccessor()
    {
        return 'App\Contracts\CounterContract';
    }
}