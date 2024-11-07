<?php

namespace Condoedge\Triggerator\Services;

class ActionsManager
{
    protected $actions = [];

    public function addActions($actions)
    {
        $this->actions = array_merge($this->actions, $actions);
    }

    public function getActions()
    {
        return $this->actions;
    }

    public function setActions($actions)
    {
        $this->actions = $actions;
    }
}