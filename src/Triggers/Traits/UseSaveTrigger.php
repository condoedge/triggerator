<?php

namespace Condoedge\Triggerator\Triggers\Traits;

use Condoedge\Triggerator\Facades\Models\TriggerSetupModel;
use Condoedge\Triggerator\Triggers\Usable\SaveTrigger;

trait UseSaveTrigger
{
    public static function booted()
    {
        static::saving(function () {
            TriggerSetupModel::forTrigger(SaveTrigger::class)
                ->whereRaw('JSON_CONTAINS(`trigger_params`,' . static::class . ', "$.model")')
                ->get()
                ->each(fn($t) => SaveTrigger::launch(['trigger' => $t]));
        });
    }
}