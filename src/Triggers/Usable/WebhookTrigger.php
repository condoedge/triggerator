<?php

namespace Condoedge\Triggerator\Triggers\Usable;

use Condoedge\Triggerator\Models\Trigger;
use Condoedge\Triggerator\Triggers\AbstractTrigger;
use Illuminate\Support\Facades\Route;

class WebhookTrigger extends AbstractTrigger
{
    static function setAllRoutes()
    {
        Trigger::setupForType(static::class, function ($trigger) {
            $route = $trigger->trigger_params->route;
            $method = $trigger->trigger_params->method;

            Route::{$method}($route, function () use ($trigger) {
                static::launch($trigger);
            });
        });
    }

    static function getName()
    {
        return __('translate.webhook-trigger-name');
    }
}