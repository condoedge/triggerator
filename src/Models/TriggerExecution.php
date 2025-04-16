<?php

namespace Condoedge\Triggerator\Models;

use Condoedge\Triggerator\Facades\Models\TriggerSetupModel;
use Illuminate\Support\Facades\DB;
use Condoedge\Utils\Models\Model;

class TriggerExecution extends Model
{
    protected $casts = [
        'execution_params' => 'object',
        'status' => ExecutionStatusEnum::class,
        'time_to_execute' => 'datetime',
        'executed_at' => 'datetime',
    ];

    // RELATIONSHIPS
    public function triggerSetup()
    {
        return $this->belongsTo(TriggerSetupModel::getClass());
    }

    // SCOPES
    public function scopeExecuted($query)
    {
        return $query->where('status', ExecutionStatusEnum::EXECUTED);
    }

    public function scopePending($query)
    {
        return $query->where('status', ExecutionStatusEnum::PENDING);
    }
    
    public function scopeForLast24Hours($query)
    {
        return $query->where('created_at', '>=', now()->subDay());
    }

    // ACTIONS
    public function deleteAssociatedJob()
    {
        if (!$this->job_id) {
            return;
        }

        DB::table('jobs')->where('id', $this->job_id)->delete();
    }

    public function delete()
    {
        $this->deleteAssociatedJob();

        parent::delete();
    }

    public static function createOrGetOne($triggerId, $params, $delay = null, $status = ExecutionStatusEnum::PENDING)
    {
        $execution = new static;

        $execution->trigger_setup_id = $triggerId;
        $execution->execution_params = $params;

        $execution->status = $status;

        $execution->time_to_execute = now()->addSeconds($delay + 1);

        $execution->save();

        return $execution;
    }

    // ELEMENTS
    public function statusPill()
    {
        return _Pill($this->status->label())->class($this->status->classes())->class('text-white');
    }
}