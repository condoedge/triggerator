<?php

namespace Condoedge\Triggerator\Models;

use Kompo\Models\Traits\EnumKompo;

enum ExecutionStatusEnum: int
{
    use EnumKompo;

    case PENDING = 1;
    case EXECUTED = 2;
    case CANCELED = 3;

    public function label()
    {
        return match ($this) {
            self::PENDING => __('translate.pending'),
            self::EXECUTED => __('translate.executed'),
            self::CANCELED => __('translate.canceled'),
        };
    }

    public function classes() {
        return match ($this) {
            self::PENDING => 'bg-warning',
            self::EXECUTED => 'bg-info',
            self::CANCELED => 'bg-danger',
        };
    }
}