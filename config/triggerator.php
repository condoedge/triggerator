<?php

use Condoedge\Triggerator\Actions\Usable\NotifyToUser;
use Condoedge\Triggerator\Models\Action;
use Condoedge\Triggerator\Models\Trigger;
use Condoedge\Triggerator\Models\TriggerExecution;
use Condoedge\Triggerator\Triggers\Usable\DateTrigger;

return [
    'triggers' => [
        DateTrigger::class,
    ],

    'actions' => [
        NotifyToUser::class,
    ],

    'models' => [
        'action' => getAppClass(App\Models\Triggerator\Action::class, Action::class),
        'trigger' => getAppClass(App\Models\Triggerator\Trigger::class, Trigger::class),
        'trigger-execution' => getAppClass(App\Models\Triggerator\TriggerExecution::class, TriggerExecution::class),
    ]
];