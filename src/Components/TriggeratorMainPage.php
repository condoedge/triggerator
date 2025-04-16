<?php

namespace Condoedge\Triggerator\Components;

use Condoedge\Utils\Kompo\Common\Form;

class TriggeratorMainPage extends Form
{
    public function render()
    {
        return _ResponsiveTabs([
            _Tab(
                new TriggeratorDashboard()
            )->label('triggerator.main-page'),
            _Tab(
                new TriggerSetupsTable()
            )->label('triggerator.triggers'),
            _Tab(
                new TriggerExecutions()
            )->label('triggerator.trigger-executions'),
        ], tabsCommonClass: 'mb-4 mr-6 ', tabsSelectedClass: 'border-b-2 pb-2 border-level1 font-semibold');
    }    
}