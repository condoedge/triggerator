<?php

use Condoedge\Triggerator\Actions\Usable\NotifyToUser;
use Condoedge\Triggerator\Models\ActionSetup;
use Condoedge\Triggerator\Models\TriggerSetup;
use Condoedge\Triggerator\Models\TriggerExecution;
use Condoedge\Triggerator\Triggers\Usable\DateTrigger;
use Condoedge\Triggerator\Triggers\Usable\SaveTrigger;
use Condoedge\Triggerator\Triggers\Usable\WebhookTrigger;

return [
    'triggers' => [
        DateTrigger::class,
        WebhookTrigger::class,
        SaveTrigger::class,
    ],

    'actions' => [
        NotifyToUser::class,
    ],

    'models' => [
        'action' => getAppClass(App\Models\Triggerator\Action::class, ActionSetup::class),
        'trigger' => getAppClass(App\Models\Triggerator\Trigger::class, TriggerSetup::class),
        'trigger-execution' => getAppClass(App\Models\Triggerator\TriggerExecution::class, TriggerExecution::class),
    ]
];