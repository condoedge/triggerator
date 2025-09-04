<?php

namespace Condoedge\Triggerator\Triggers\Traits;

use Condoedge\Triggerator\Facades\Models\TriggerSetupModel;
use Condoedge\Triggerator\Triggers\Usable\SaveTrigger;

trait UseSaveTrigger
{
    public static function booted()
    {
        static::saving(function ($model) {
            event(new \Condoedge\Triggerator\Events\ModelSaved($model));
        });
    }
}