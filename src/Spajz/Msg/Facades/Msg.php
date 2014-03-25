<?php namespace Spajz\Msg\Facades;

use Illuminate\Support\Facades\Facade;

class Msg extends Facade
{
    /**
     * Get the registered component.
     *
     * @return object
     */
    protected static function getFacadeAccessor()
    {
        return 'msg';
    }
}
