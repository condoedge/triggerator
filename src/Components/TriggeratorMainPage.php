<?php

namespace Condoedge\Triggerator\Components;

use Kompo\Auth\Common\Form;

class TriggeratorMainPage extends Form
{
    public function render()
    {
        return _ResponsiveTabs([
            _Tab(
                new TriggeratorDashboard()
            )->label('translate.main-page'),
            _Tab(
                new TriggerSetupsTable()
            )->label('translate.triggers'),
            _Tab(
                new TriggerExecutions()
            )->label('translate.trigger-executions'),
        ], tabsCommonClass: 'mb-4 mr-6 ', tabsSelectedClass: 'border-b-2 pb-2 border-level1 font-semibold');
    }    
}