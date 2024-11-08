<?php

namespace Condoedge\Triggerator\Models;

use Condoedge\Triggerator\Actions\Contract\ActionContract;
use Condoedge\Triggerator\Facades\Models\TriggerSetupModel;
use Kompo\Auth\Models\Model;

class ActionSetup extends Model
{
    protected $casts = [
        'action_params' => 'object',
    ];

    // RELATIONSHIPS
    public function trigger()
    {related: 
        return $this->belongsTo(TriggerSetupModel::getClass());
    }

    // ATTRIBUTES
    public function getActionAttribute()
    {
        if(!$this->action_namespace || !class_exists($this->action_namespace)) return null;

        return new $this->action_namespace;
    }

    // ACTIONS
    public function execute($params)
    {
        return $this->action?->execute($params);
    }
}