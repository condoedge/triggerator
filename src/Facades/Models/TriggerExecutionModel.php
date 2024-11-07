<?php

namespace Condoedge\Triggerator\Facades\Models;

use Kompo\Komponents\Form\KompoModelFacade;

/**
 * @mixin \Condoedge\Triggerator\Models\TriggerExecution
 */
class TriggerExecutionModel extends KompoModelFacade
{
    protected static function getModelBindKey()
    {
        return 'trigger-execution-model';
    }
}