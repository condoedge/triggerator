<?php

namespace Condoedge\Triggerator\Triggers;

use Condoedge\Triggerator\Facades\Actions;
use Condoedge\Triggerator\Models\TriggerSetup;
use Condoedge\Triggerator\Triggers\Contracts\TriggerContract;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class AbstractSetupModelTrigger implements TriggerContract
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $showDelay = true;

    public static function afterSetup(array $params = [])
    {
        return null;
    }

    static function launch(array $params = [])
    {
        /**
         * @var TriggerSetup $trigger
         */
        $trigger = objectFromArray($params)->trigger;

        $trigger->execute($params);
    }

    abstract static function getName();

    static function getForm(array $params = [])
    {
        return _Rows(
            self::delayInputs(),
        );
    }

    protected static function delayInputs()
    {
        return [
            _Input('triggerator.delay')->type('number')->default(0)->name('delay'),
        ];
    }

    public static function integrityValidators()
    {
        return [];
    }

    public static function possibleActions()
    {
        return Actions::getActions();
    }
}