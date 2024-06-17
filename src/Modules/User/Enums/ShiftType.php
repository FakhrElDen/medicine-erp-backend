<?php

namespace Modules\User\Enums;

use Illuminate\Support\Facades\App;

class ShiftType
{
    public const MORNING = 1;

    public const NIGHT = 0;

    public static function all()
    {
        return [
            'morning' => self::MORNING,
            'night' => self::NIGHT,
        ];
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::MORNING:
                return App::isLocale('ar') ? 'مندوب صباحي' : 'morning';
            case self::NIGHT:
                return App::isLocale('ar') ? 'مندوب مسائي' : 'night';
            default:
                throw new \InvalidArgumentException("Invalid shift type value: $value");
        }
    }
}
