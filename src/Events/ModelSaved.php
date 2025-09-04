<?php

namespace Condoedge\Triggerator\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;

class ModelSaved
{
    use Dispatchable;

    public $model;
    public $modelClass;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->modelClass = get_class($model);
    }

    public function toArray()
    {
        return [
            'model' => $this->model,
            'modelClass' => $this->modelClass,
        ];
    }
}