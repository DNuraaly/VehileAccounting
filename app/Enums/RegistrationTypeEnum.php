<?php

namespace App\Enums;

enum RegistrationTypeEnum: string
{
    case PRIMARY = 'primary';
    case RE_REGISTERED = 'reregistration';

    /**
     * Возвращает все доступные значения интервалов в виде массива.
     *
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
