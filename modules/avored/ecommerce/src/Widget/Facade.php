<?php

namespace AvoRed\Ecommerce\Widget;

use Illuminate\Support\Facades\Facade as LaravelFacade;

class Facade extends LaravelFacade
{
    protected static function getFacadeAccessor()
    {
        return 'widget';
    }
}
