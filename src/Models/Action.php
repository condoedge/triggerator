<?php

namespace Condoedge\Triggerator\Models;

use Condoedge\Triggerator\Actions\Contract\ActionContract;
use Condoedge\Triggerator\Facades\Models\TriggerModel;
use Kompo\Auth\Models\Model;

class Action extends Model
{
    protected $casts = [
        'action_params' => 'object',
    ];

    public function trigger()
    {related: 
        return $this->belongsTo(TriggerModel::getClass());
    }

    public function getActionAttribute(): ?ActionContract
    {
        if(!$this->action_namespace || !class_exists($this->action_namespace)) return null;

        return (new ($this->action_namespace)($this));
    }

    public function execute($params)
    {
        return $this->action?->execute($params);
    }
}