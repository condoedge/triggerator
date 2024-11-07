<?php

namespace Condoedge\Triggerator\Services;

class TriggersManager
{
    protected $triggers = [];
    protected $actionBindings = [];

    public function addTriggers($triggers)
    {
        $this->triggers = array_merge($this->triggers, $triggers);

        $this->bindTriggersWithRightActions($triggers);
    }

    public function getTriggers()
    {
        return $this->triggers;
    }

    public function setTriggers($triggers)
    {
        $this->triggers = $triggers;

        $this->bindTriggersWithRightActions($triggers);
    }

    public function bindTriggersWithRightActions($triggers)
    {
        collect(value: $triggers)->each(function ($trigger) {
            $this->actionBindings[$trigger] =$trigger::possibleActions();
        });
    }

    public function getActionBindingsForTrigger($trigger)
    {
        return $this->actionBindings[$trigger] ?? [];
    }

    public function addActionBinding($trigger, $actions)
    {
        $this->actionBindings[$trigger] = array_merge($this->actionBindings[$trigger] ?? [], $actions);
    }

    public function setActionsBindingsByTrigger($trigger, $actions)
    {
        $this->actionBindings[$trigger] = $actions;
    }
}