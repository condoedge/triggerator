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
            $this->actionBindings[$trigger] = $trigger::possibleActions();
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

    public function loadEventListeners()
    {
        collect($this->triggers)->each(function ($triggerClass) {
            $eventClass = $triggerClass::getListeningEvent();
            
            if ($eventClass && class_exists($eventClass)) {
                \Illuminate\Support\Facades\Event::listen($eventClass, function ($event) use ($triggerClass) {
                    $params = [];
                    
                    // Extract data from the event object
                    if (is_object($event)) {
                        $params = method_exists($event, 'toArray') 
                            ? $event->toArray() 
                            : json_decode(json_encode($event), true);
                    }
                    
                    $params['event'] = $event;
                    
                    // Find trigger setups for this trigger type filtered by event params
                    $baseQuery = \Condoedge\Triggerator\Models\TriggerSetup::forTrigger($triggerClass);
                    $filteredQuery = $triggerClass::filterTriggersForEvent($event, $baseQuery);
                    $triggerSetups = $filteredQuery->get();
                    
                    $triggerSetups->each(function ($triggerSetup) use ($params, $triggerClass) {
                        $triggerClass::launch(array_merge($params, ['trigger' => $triggerSetup]));
                    });
                });
            }
        });
    }
}