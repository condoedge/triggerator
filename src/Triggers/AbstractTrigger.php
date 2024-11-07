<?php

namespace Condoedge\Triggerator\Triggers;

use Condoedge\Triggerator\Facades\Actions;
use Condoedge\Triggerator\Triggers\Contracts\TriggerContract;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class AbstractTrigger implements TriggerContract
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $trigger;
    protected $params;
    public $showDelay = true;

    public function __construct($trigger, $params = [])
    {
        $this->trigger = $trigger;
        $this->params = $params;
    }

    public function getTrigger()
    {
        return $this->trigger;
    }

    public function getParams()
    {
        return (object) array_merge((array) $this->getTrigger()->trigger_params, (array) $this->params);
    }

    public static function afterSetup($trigger, $params = [])
    {
        return null;
    }

    static function launch($trigger, $params = [])
    {
        event(new static($trigger, $params));
    }

    abstract static function getName();

    function getForm()
    {
        return _Rows(
            $this->delayInputs(),
        );
    }

    protected function delayInputs()
    {
        return [
            _Input('translate.delay')->type('number')->default(0)->name('delay'),
        ];
    }

    public function integrityValidators()
    {
        return [];
    }

    public static function possibleActions()
    {
        return Actions::getActions();
    }
}