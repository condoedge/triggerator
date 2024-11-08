<?php
namespace Condoedge\Triggerator\Triggers\Usable;

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

        static::launch(['trigger' => $triggerModel]);
    }

    static function getName()
    {
        return __('translate.date-trigger-name');
    }

    public static function integrityValidators()
    {
        return [
            'date' => 'required|date|after:today',
        ];
    }
}