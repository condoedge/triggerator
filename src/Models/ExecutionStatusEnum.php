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
            self::PENDING => __('triggerator.pending'),
            self::EXECUTED => __('triggerator.executed'),
            self::CANCELED => __('triggerator.canceled'),
        };
    }

    public function classes() {
        return match ($this) {
            self::PENDING => 'bg-warning',
            self::EXECUTED => 'bg-positive',
            self::CANCELED => 'bg-danger',
        };
    }
}