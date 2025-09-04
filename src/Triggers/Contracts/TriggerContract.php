<?php

namespace Condoedge\Triggerator\Triggers\Contracts;

interface TriggerContract
{
    public static function launch(array $params);

    public static function possibleActions();

    public static function getName();

    // FORMS
    public static function getForm(array $params);

    public static function integrityValidators();

    public static function afterSetup(array $params);

    // EVENT INTEGRATION
    public static function getListeningEvent(): ?string;

    public static function shouldExecuteForEvent($event, $triggerParams): bool;

    public static function filterTriggersForEvent($event, $query);
}