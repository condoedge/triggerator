<?php
namespace Condoedge\Triggerator\Listeners;

use Condoedge\Triggerator\Triggers\Contracts\TriggerContract;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;


class TriggerListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(TriggerContract $trigger)
    {
        $trigger->getTrigger()->execute();
    }
}