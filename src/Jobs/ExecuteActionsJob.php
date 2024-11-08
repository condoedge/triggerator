<?php

namespace Condoedge\Triggerator\Jobs;

use Condoedge\Triggerator\Facades\Models\TriggerExecutionModel;
use Condoedge\Triggerator\Facades\Models\TriggerSetupModel;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Condoedge\Triggerator\Models\ExecutionStatusEnum;

class ExecuteActionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $triggerId;
    protected $executionId;

    public function __construct($triggerId, $executionId)
    {
        $this->triggerId = $triggerId;
        $this->executionId = $executionId;
    }

    public function handle()
    {
        $trigger = TriggerSetupModel::findOrFail($this->triggerId);
        $execution = TriggerExecutionModel::findOrFail($this->executionId);

        $trigger->executeActions();

        $execution->status = ExecutionStatusEnum::EXECUTED;
        $execution->executed_at = now();
        $execution->save();
    }
}
