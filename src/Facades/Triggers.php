<?php

namespace Condoedge\Triggerator\Facades;

use Illuminate\Support\Facades\Facade;

class Triggers extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'triggers-manager';
    }
}