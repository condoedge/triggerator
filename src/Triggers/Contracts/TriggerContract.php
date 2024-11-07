<?php

namespace Condoedge\Triggerator\Triggers\Contracts;

use Condoedge\Triggerator\Models\Trigger;

interface TriggerContract
{
    /**
     * Getting the instance of the trigger model
     * @return Trigger|null
     */
    public function getTrigger();

    public function getParams();

    public static function afterSetup($trigger, $params = []);

    static function launch($trigger, $params = []);
}