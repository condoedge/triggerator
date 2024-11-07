<?php

namespace Condoedge\Triggerator\Facades\Models;

use Kompo\Komponents\Form\KompoModelFacade;

/**
 * @mixin \Condoedge\Triggerator\Models\Trigger
 */
class TriggerModel extends KompoModelFacade
{
    protected static function getModelBindKey()
    {
        return 'trigger-model';
    }
}