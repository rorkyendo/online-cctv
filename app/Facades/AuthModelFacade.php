<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class AuthModelFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'AuthModel';
    }
}
