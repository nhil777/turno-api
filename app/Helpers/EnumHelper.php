<?php

namespace App\Helpers;

trait EnumHelper
{
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
