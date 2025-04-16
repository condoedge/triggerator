<?php
namespace Condoedge\Triggerator\Components;

use Condoedge\Triggerator\Facades\Models\TriggerSetupModel;
use Condoedge\Utils\Kompo\Common\Table;

class TriggerSetupsTable extends Table
{
    public function top()
    {
        return _FlexBetween(
            _Html('triggerator.triggers')->class('text-2xl'),
            _LinkButton('triggerator.create-trigger')->href('triggerator.form'),
        );
    }

    public function query()
    {
        return TriggerSetupModel::query();
    }

    public function headers()
    {
        return [
            _Th('triggerator.trigger-name'),
            _Th('triggerator.trigger-namespace'),
            _Th('triggerator.trigger-params'),
            _Th('triggerator.count-of-actions'),
            _Th('triggerator.delay'),
            _Th('')->class('w-8')
        ];
    }

    public function render($triggerSetup)
    {
        return _TableRow(
            _Html($triggerSetup->name),
            _Html($triggerSetup->trigger->getName()),
            _Rows(
                collect($triggerSetup->trigger_params)->map(function ($value, $key) {
                    return _Html($key . ': ' . $value);
                }),
            ),

            _Html($triggerSetup->actions()->count()),

            _Html($triggerSetup->trigger->showDelay ? $triggerSetup->delay : '-'),

            _Delete($triggerSetup)->class('hover:text-red-600'),
        )->href('triggerator.form', ['id' => $triggerSetup->id]);
    }
    
}