<?php

namespace Condoedge\Triggerator\Actions\Contract;

interface ActionContract 
{
    public function execute(object $params);

    public static function getName();
}