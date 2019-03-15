<?php

namespace RegApi\Facades;

use Illuminate\Support\Facades\Facade;
use RegApi\Services\RegApi;

class Reg extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return RegApi::class;
    }
}
