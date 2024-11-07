<?php

namespace Condoedge\Triggerator\Components;

use Condoedge\Triggerator\Facades\Models\TriggerExecutionModel;
use Condoedge\Triggerator\Models\ExecutionStatusEnum;
use Kompo\Auth\Common\Table;

class TriggerExecutions extends Table
{
    public function top()
    {
        return _FlexBetween(
            _Html('translate.triggers-executions')->class('text-2xl'),
            _MultiSelect()->options(ExecutionStatusEnum::optionsWithLabels())->class('!mb-0')
                ->default([ExecutionStatusEnum::PENDING->value, ExecutionStatusEnum::EXECUTED->value])
                ->name('status', false)->filter(),
        );
    }

    public function query()
    {
        return TriggerExecutionModel::when(request('status'), fn($q) => $q->whereIn('status', request('status')));
    }

    public function headers()
    {
        return [
            _Th('translate.trigger-name'),
            _Th('translate.status'),
            _Th('translate.time-to-execute'),
        ];
    }

    public function render($execution)
    {
        return _TableRow(
            _Html($execution->trigger->name),
            $execution->statusPill(),
            _Html($execution->time_to_execute->format('Y-m-d H:i')),
        );
    }
}