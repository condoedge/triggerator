<?php

namespace Condoedge\Triggerator\Components;

use Condoedge\Triggerator\Facades\Models\TriggerExecutionModel;
use Condoedge\Triggerator\Facades\Models\TriggerSetupModel;
use Kompo\Auth\Common\Form;

class TriggeratorDashboard extends Form
{
    public function render()
    {
        return _Rows(
            _CardIconStat('box', 'triggerator.triggers-quantity', _Html(TriggerSetupModel::count())->class('text-2xl'))->class('bg-warning text-white'),
            _CardIconStat('box', 'triggerator.executions-in-last-24-hours', _Html(TriggerExecutionModel::executed()->forLast24Hours()->count())->class('text-2xl'))->class('bg-info text-white'),  
        );
    }
}