<?php
namespace Condoedge\Triggerator\Components;

use Condoedge\Triggerator\Facades\Models\TriggerSetupModel;
use Kompo\Auth\Common\Table;

class TriggerSetupsTable extends Table
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
        return TriggerSetupModel::query();
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