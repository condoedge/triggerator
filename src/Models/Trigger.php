<?php

namespace Condoedge\Triggerator\Models;

use Condoedge\Triggerator\Facades\Models\ActionModel;
use Condoedge\Triggerator\Facades\Models\TriggerExecutionModel;
use Condoedge\Triggerator\Jobs\ExecuteActionsJob;
use Condoedge\Triggerator\Jobs\ExecuteCallbackJob;
use Condoedge\Triggerator\Triggers\Contracts\TriggerContract;
use Illuminate\Bus\Dispatcher;
use Kompo\Auth\Models\Model;

class Trigger extends Model {
    protected $casts = [
        'trigger_params' => 'object',
    ];

    public function getTriggerAttribute()
    {
        if(!$this->trigger_namespace || !class_exists($this->trigger_namespace)) return null;

        return new ($this->trigger_namespace)($this, $this->trigger_params);
    }

    public function actions()
    {
        return $this->hasMany(ActionModel::getClass());
    }

    public function executions()
    {
        return $this->hasMany(TriggerExecutionModel::getClass());
    }

    public function scopeForTrigger($query, $trigger)
    {
        return $query->where('trigger_namespace', $trigger);
    }

    public static function setupForType($trigger, $callback) 
    {
        $triggers = static::forTrigger($trigger)->get();

        $triggers->each(function ($trigger) use ($callback) {
            $callback($trigger);
        });
    }

    public function execute()
    {
        $execution = TriggerExecution::createOrGetOne($this->id, $this->trigger->getParams(), $this->delay);

        if ($this->delay) {
            $job = new ExecuteActionsJob($this->id, $execution->id);
            $jobId = app(Dispatcher::class)->dispatch($job->delay($this->delay));

            $execution->job_id = $jobId;
            $execution->save();

            return;
        }

        $this->executeActions();
    }

    public function delete()
    {
        $this->executions()->pending()->delete();

        parent::delete();
    }

    public function executeActions()
    {
        $this->actions->each(function ($action) {
            $action->execute((object) array_merge((array) $action->action_params, (array) $this->trigger->getParams()));
        });
    }
}