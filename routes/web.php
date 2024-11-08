<?php

use Condoedge\Triggerator\Models\TriggerSetup;
use Condoedge\Triggerator\Triggers\Usable\WebhookTrigger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::layout('layouts.dashboard')->middleware(['auth'])->group(function(){
    Route::get('triggerator', \Condoedge\Triggerator\Components\TriggeratorMainPage::class)->name('triggerator.dashboard');

    Route::get('trigger-form/{id?}', \Condoedge\Triggerator\Components\TriggerSetupFormPage::class)->name('triggerator.form');
});

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