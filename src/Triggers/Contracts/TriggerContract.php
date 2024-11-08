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
}