<?php

namespace Condoedge\Triggerator\Triggers\Usable;

use Condoedge\Triggerator\Events\WebhookReceived;
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
            $triggers = TriggerSetup::getForTypeCached(WebhookTrigger::class);

            $triggers->each(function ($trigger) {
                $route = $trigger->trigger_params->route;
                $method = $trigger->trigger_params->method;
        
                Route::{$method}($route, function (Request $request) use ($trigger, $route, $method) {
                    try{
                        event(new WebhookReceived($request->all(), $route, $method));
                    } catch (\Exception $e) {
                        return response()->json(['message' => $e->getMessage()], 500);
                    }
        
                    return response()->json(['message' => 'Trigger executed']);
                });
            });
        }
    }

    public static function getListeningEvent(): ?string
    {
        return WebhookReceived::class;
    }

    public static function shouldExecuteForEvent($event, $triggerParams): bool
    {
        if (!$event instanceof \Condoedge\Triggerator\Events\WebhookReceived) {
            return false;
        }

        // Check if this trigger is configured for this route/method
        $configuredRoute = $triggerParams->route ?? null;
        $configuredMethod = $triggerParams->method ?? 'GET';

        // Match route (if configured) and method
        return (!$configuredRoute || $event->route === $configuredRoute) 
            && $event->method === $configuredMethod;
    }

    public static function filterTriggersForEvent($event, $query)
    {
        if (!$event instanceof \Condoedge\Triggerator\Events\WebhookReceived) {
            return $query->whereRaw('1 = 0'); // No results
        }

        $query = $query->whereRaw("JSON_EXTRACT(trigger_params, '$.method') = ?", [json_encode($event->method)]);

        if ($event->route) {
            $query = $query->whereRaw("JSON_EXTRACT(trigger_params, '$.route') = ?", [json_encode($event->route)]);
        }

        return $query;
    }
}
