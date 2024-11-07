<?php

namespace Condoedge\Triggerator\Actions\Usable\Traits;

use Condoedge\Triggerator\Models\Trigger;
use Condoedge\Triggerator\Triggers\Usable\SaveTrigger;

trait UseSaveTrigger
{
    public function booted()
    {
        static::saving(function ($model) {
            Trigger::setupForType(static::class, function ($trigger) use ($model) {
                SaveTrigger::launch($trigger, ['model' => $model]);
            });
        });
    }

    public function possibleActions()
    {
        return [

        ];
    }
}