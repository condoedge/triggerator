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
                ->whereRaw("JSON_EXTRACT(trigger_params, '$.model') = '" . json_encode(static::class) . "'")
                ->get()
                ->each(fn($t) => SaveTrigger::launch(['trigger' => $t]));
        });
    }
}