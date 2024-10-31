<?php

use Illuminate\Support\Facades\Route;

Route::layout('layouts.dashboard')->middleware(['auth'])->group(function(){
    Route::get('triggerator', \Condoedge\Triggerator\Components\TriggeratorDashboard::class)->name('triggerator.dashboard');
});