<?php
namespace Condoedge\Triggerator\Components;

use Condoedge\Triggerator\Facades\Models\TriggerModel;
use Kompo\Auth\Common\Table;

class TriggersTable extends Table
{
    public function top()
    {
        return _FlexBetween(
            _Html('translate.triggers')->class('text-2xl'),
            _LinkButton('translate.create-trigger')->href('triggerator.form'),
        );
    }

    public function query()
    {
        return TriggerModel::query();
    }

    public function headers()
    {
        return [
            _Th('translate.trigger-name'),
            _Th('translate.trigger-namespace'),
            _Th('translate.trigger-params'),
            _Th('translate.count-of-actions'),
            _Th('translate.delay'),
            _Th('')->class('w-8')
        ];
    }

    public function render($trigger)
    {
        return _TableRow(
            _Html($trigger->name),
            _Html($trigger->trigger::getName()),
            _Rows(
                collect($trigger->trigger_params)->map(function ($value, $key) {
                    return _Html($key . ': ' . $value);
                }),
            ),

            _Html($trigger->actions()->count()),

            _Html($trigger->trigger->showDelay ? $trigger->delay : '-'),

            _Delete($trigger)->class('hover:text-red-600'),
        )->href('triggerator.form', ['id' => $trigger->id]);
    }
    
}