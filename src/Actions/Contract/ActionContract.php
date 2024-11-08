<?php

namespace Condoedge\Triggerator\Actions\Contract;

interface ActionContract 
{
    public static function execute(array $params);

    public static function getName();

    // FORM
    public static function getForm(array $params);

    public static function integrityValidators();
}