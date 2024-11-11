<?php

namespace Condoedge\Triggerator\Facades\Models;

use Kompo\Komponents\Form\KompoModelFacade;

/**
 * @mixin \Condoedge\Triggerator\Models\TriggerSetup
 */
class TriggerSetupModel extends KompoModelFacade
{
    protected static function getModelBindKey()
    {
        return 'trigger-model';
    }
}