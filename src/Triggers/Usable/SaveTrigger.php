<?php

namespace Condoedge\Triggerator\Triggers\Usable;

use Condoedge\Triggerator\Triggers\AbstractTrigger;

class SaveTrigger extends AbstractTrigger
{
    function getForm()
    {
        return _Rows(
            $this->delayInputs(),
        );
    }

    static function getName()
    {
        return __('translate.save-trigger-name');
    }
}
