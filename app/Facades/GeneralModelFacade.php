<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class GeneralModelFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'GeneralModel';
    }
}
