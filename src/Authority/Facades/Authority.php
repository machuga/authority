<?php

namespace Authority\Facades;
use Illuminate\Support\Facades\Facade;

class Authority {
    /**
     * Get the registered component.
     *
     * @return object
     */
    protected static function getFacadeAccessor(){ return 'authority'; }

}
