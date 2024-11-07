<?php

namespace Condoedge\Triggerator\Models;

use Condoedge\Triggerator\Facades\Models\TriggerModel;
use Illuminate\Support\Facades\DB;
use Kompo\Auth\Models\Model;

class TriggerExecution extends Model
{
    protected $casts = [
        'execution_params' => 'object',
        'status' => ExecutionStatusEnum::class,
        'time_to_execute' => 'datetime',
        'executed_at' => 'datetime',
    ];

    public function trigger()
    {
        return $this->belongsTo(TriggerModel::getClass());
    }

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

    public function deleteAssociatedJob()
    {
        if (!$this->job_id) {
            return;
        }

        DB::table('jobs')->where('id', $this->job_id)->delete();
    }

    public static function createOrGetOne($triggerId, $params, $delay = null, $status = ExecutionStatusEnum::PENDING)
    {
        $execution = new static;

        $execution->trigger_id = $triggerId;
        $execution->execution_params = $params;

        $execution->status = $status;

        $execution->time_to_execute = now()->addSeconds($delay);

        $execution->save();

        return $execution;
    }

    public function statusPill()
    {
        return _Pill($this->status->label())->class($this->status->classes())->class('text-white');
    }
}