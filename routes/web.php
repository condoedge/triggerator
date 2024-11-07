<?php

use Condoedge\Triggerator\Triggers\Usable\WebhookTrigger;
use Illuminate\Support\Facades\Route;

Route::layout('layouts.dashboard')->middleware(['auth'])->group(function(){
    Route::get('triggerator', \Condoedge\Triggerator\Components\TriggeratorMainPage::class)->name('triggerator.dashboard');

    Route::get('trigger-form/{id?}', \Condoedge\Triggerator\Components\TriggerFormPage::class)->name('triggerator.form');
});

WebhookTrigger::setAllRoutes();