<?php
namespace Condoedge\Triggerator\Triggers\Usable;

use Condoedge\Triggerator\Events\DateTriggered;
use Condoedge\Triggerator\Triggers\AbstractSetupModelTrigger;

class DateTrigger extends AbstractSetupModelTrigger
{
    public $showDelay = false;

    public static function getForm(array $params = [])
    {
        return _Rows(
            _DateTime()->name('date', false)->default($params['date'] ?? ''),
        );
    }

    static function afterSetup(array $params = [])
    {
        $triggerModel = $params['trigger'] ?? null;

        $timeToTrigger = now()->diffInSeconds($triggerModel->trigger_params->date);

        $triggerModel->delay = $timeToTrigger;

        $triggerModel->save();

        $triggerModel->executions()->pending()->delete();

        event(new DateTriggered($triggerModel->trigger_params->date, $triggerModel->id));
    }

    static function getName()
    {
        return __('triggerator.date-trigger-name');
    }

    public static function integrityValidators()
    {
        return [
            'date' => 'required|date|after:today',
        ];
    }

    public static function getListeningEvent(): ?string
    {
        return DateTriggered::class;
    }

    public static function filterTriggersForEvent($event, $query)
    {
        if (!$event instanceof \Condoedge\Triggerator\Events\DateTriggered) {
            return $query->whereRaw('1 = 0'); // No results
        }

        // If event has a specific trigger ID, filter by it
        if ($event->triggerId) {
            return $query->where('id', $event->triggerId);
        }

        // Otherwise filter by date
        return $query->whereRaw("JSON_EXTRACT(trigger_params, '$.date') = ?", [json_encode($event->date)]);
    }
}