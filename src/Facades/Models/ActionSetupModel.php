<?php

namespace Condoedge\Triggerator\Facades\Models;

use Kompo\Komponents\Form\KompoModelFacade;

/**
 * @mixin \Condoedge\Triggerator\Models\ActionSetup
 */
class ActionSetupModel extends KompoModelFacade
{
    protected static function getModelBindKey()
    {
        return 'action-model';
    }
}