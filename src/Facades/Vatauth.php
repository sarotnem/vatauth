<?php

namespace Theomessin\Vatauth\Facades;

use Illuminate\Support\Facades\Facade;

class Vatauth extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
    	return 'Vatauth';
    }

}