<?php

namespace Condoedge\Triggerator\Facades\Models;

use Kompo\Komponents\Form\KompoModelFacade;

/**
 * @mixin \Condoedge\Triggerator\Models\Action
 */
class ActionModel extends KompoModelFacade
{
    protected static function getModelBindKey()
    {
        return 'action-model';
    }
}