<?php

namespace Condoedge\Triggerator\Actions;

use Condoedge\Triggerator\Actions\Contract\ActionContract;

abstract class AbstractAction implements ActionContract
{
    abstract public static function execute(array $params);

    static function getForm(array $params)
    {
        return _Rows();
    }

    public static function integrityValidators()
    {
        return [];
    }
}