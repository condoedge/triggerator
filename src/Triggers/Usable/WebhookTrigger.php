<?php

namespace Condoedge\Triggerator\Triggers\Usable;

use Condoedge\Triggerator\Triggers\AbstractSetupModelTrigger;

class WebhookTrigger extends AbstractSetupModelTrigger
{
    static function getName()
    {
        return __('triggerator.webhook-trigger-name');
    }

    static function getForm(array $params = [])
    {
        return _Rows(
            _Input('triggerator.route')->name('route', false)->default($params['route'] ?? ''),
            _Select('triggerator.method')->name('method', false)
                ->options(collect(static::httpMethods())->mapWithKeys(fn ($method) => [$method => $method]))
                ->default($params['method'] ?? 'GET'),
        );
    }

    protected static function httpMethods()
    {
        return [
            'GET',
            'POST',
            'PUT',
            'PATCH',
            'DELETE',
        ];
    }

    public static function integrityValidators()
    {
        return [
            'route' => 'required|string|regex:/^[a-zA-Z0-9-_\/]+$/',
            'method' => 'required|string|in:' . implode(',', static::httpMethods()),
        ];
    }
}
