<?php

namespace Condoedge\Triggerator\Facades;

use Illuminate\Support\Facades\Facade;

class Actions extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'actions-manager';
    }
}