<?php
namespace Condoedge\Triggerator\Triggers\Usable;

use Condoedge\Triggerator\Triggers\AbstractTrigger;

class DateTrigger extends AbstractTrigger
{
    public $showDelay = false;

    public function getForm()
    {
        return _Rows(
            _DateTime()->name('date', false)->default($this->trigger?->trigger_params?->date),
        );
    }

    static function afterSetup($triggerModel, $params = [])
    {
        $timeToTrigger = now()->diffInSeconds($triggerModel->trigger_params->date);

        $triggerModel->delay = $timeToTrigger;

        $triggerModel->save();

        $triggerModel->executions()->pending()->delete();

        static::launch($triggerModel);
    }

    static function getName()
    {
        return __('translate.date-trigger-name');
    }

    public function integrityValidators()
    {
        return [
            'date' => 'required|date|after:today',
        ];
    }
}