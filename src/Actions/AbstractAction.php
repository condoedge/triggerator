<?php

namespace Condoedge\Triggerator\Actions;

use Condoedge\Triggerator\Actions\Contract\ActionContract;

abstract class AbstractAction implements ActionContract
{
    protected $action;
    
    public function __construct($action)
    {
        $this->action = $action;
    }

    abstract public function execute(object $params);

    function getForm()
    {
        return _Rows();
    }

    public function integrityValidators()
    {
        return [];
    }
}