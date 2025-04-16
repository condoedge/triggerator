<?php

namespace Condoedge\Triggerator\Models;

use Condoedge\Triggerator\Facades\Models\ActionSetupModel;
use Condoedge\Triggerator\Facades\Models\TriggerExecutionModel;
use Condoedge\Triggerator\Jobs\ExecuteActionsJob;
use Illuminate\Bus\Dispatcher;
use Condoedge\Utils\Models\Model;

class TriggerSetup extends Model {
    protected $casts = [
        'trigger_params' => 'object',
    ];

    // RELATIONSHIPS
    public function actions()
    {
        return $this->hasMany(ActionSetupModel::getClass());
    }

    public function executions()
    {
        return $this->hasMany(TriggerExecutionModel::getClass());
    }

    // ATTRIBUTES
    public function getTriggerAttribute()
    {
        if(!$this->trigger_namespace || !class_exists($this->trigger_namespace)) return null;

        return new $this->trigger_namespace;
    }

    // SCOPES
    public function scopeForTrigger($query, $trigger)
    {
        return $query->where('trigger_namespace', $trigger);
    }

    // ACTIONS
    public static function getForType($trigger) 
    {
        $triggers = static::forTrigger($trigger)->get();

        return $triggers;
    }

    public static function getCacheKey($trigger)
    {
        return 'triggerator.triggers.' . $trigger;
    }

    public static function getForTypeCached($trigger) 
    {
        return cache()->rememberForever(static::getCacheKey($trigger), function () use ($trigger) {
            return static::getForType($trigger);
        });
    }


    public function execute(array $params)
    {
        $execution = TriggerExecution::createOrGetOne($this->id, $params, $this->delay);

        if ($this->delay) {
            $job = new ExecuteActionsJob($this->id, $execution->id);
            $jobId = app(Dispatcher::class)->dispatch($job->delay($this->delay));

            $execution->job_id = $jobId;
            $execution->save();

            return;
        }

        $this->executeActions($params);
    }

    public function delete()
    {
        $this->executions()->pending()->get()->each->delete();

        parent::delete();
    }

    public function executeActions(array $params = [])
    {
        $this->actions->each(function ($action) use ($params) {
            $action->execute(array_merge((array) $action->action_params, $params));
        });
    }
}