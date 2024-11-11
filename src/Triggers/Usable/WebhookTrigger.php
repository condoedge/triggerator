<?php

namespace Condoedge\Triggerator\Triggers\Usable;

use Condoedge\Triggerator\Models\TriggerSetup;
use Condoedge\Triggerator\Triggers\AbstractSetupModelTrigger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

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

    static function setListenerRoutes()
    {
        if (Schema::hasTable('trigger_setups')) {
            $triggers = TriggerSetup::getForType(WebhookTrigger::class);
        
            $triggers->each(function ($trigger) {
                $route = $trigger->trigger_params->route;
                $method = $trigger->trigger_params->method;
        
                Route::{$method}($route, function (Request $request) use ($trigger) {
                    try{
                        WebhookTrigger::launch(array_merge($request->all(), ['trigger' => $trigger]));
                    } catch (\Exception $e) {
                        return response()->json(['message' => $e->getMessage()], 500);
                    }
        
                    return response()->json(['message' => 'Trigger executed']);
                });
            });
        }
    }
}
