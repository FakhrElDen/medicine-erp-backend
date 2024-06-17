<?php

namespace Modules\Setting\Enums;

use Illuminate\Support\Facades\App;

class ComplainType
{
    public const NOT_SOLVE = 0;

    public const SOLVE = 1;

    public static function all()
    {
        return [
            'solve' => self::SOLVE,
            'notSolve' => self::NOT_SOLVE,
        ];
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::SOLVE:
                return App::isLocale('ar') ? 'تم الحل' : 'Solve';
            case self::NOT_SOLVE:
                return App::isLocale('ar') ? 'لم يتم الحل' : 'Not Solve';
            default:
                throw new \InvalidArgumentException("Invalid client type value: $value");
        }
    }
}
