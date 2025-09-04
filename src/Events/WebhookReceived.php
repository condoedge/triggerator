<?php

namespace Condoedge\Triggerator\Events;

use Illuminate\Foundation\Events\Dispatchable;

class WebhookReceived
{
    use Dispatchable;

    public $data;
    public $route;
    public $method;

    public function __construct($data = [], $route = null, $method = 'POST')
    {
        $this->data = $data;
        $this->route = $route;
        $this->method = $method;
    }

    public function toArray()
    {
        return [
            'data' => $this->data,
            'route' => $this->route,
            'method' => $this->method,
        ];
    }
}