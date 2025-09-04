<?php

namespace Condoedge\Triggerator\Events;

use Illuminate\Foundation\Events\Dispatchable;

class DateTriggered
{
    use Dispatchable;

    public $date;
    public $triggerId;

    public function __construct($date, $triggerId = null)
    {
        $this->date = $date;
        $this->triggerId = $triggerId;
    }

    public function toArray()
    {
        return [
            'date' => $this->date,
            'triggerId' => $this->triggerId,
        ];
    }
}