<?php

namespace Modules\Client\Enums;

use Illuminate\Support\Facades\App;

class DayShifts
{
    public const MORNING = 0;

    public const NIGHT = 1;

    public static function all()
    {
        App::isLocale('ar') ?
            $pharmacy_follow_up = [
                [
                    "name" => 'صباحي',
                    "value" => self::MORNING,
                ],
                [
                    "name" => 'مسائي',
                    "value" => self::NIGHT,
                ],
            ]
            :
            $pharmacy_follow_up = [
                [
                    "name" => 'morning',
                    "value" => self::MORNING,
                ],
                [
                    "name" => 'night',
                    "value" => self::NIGHT,
                ],
            ];
        return $pharmacy_follow_up;
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::MORNING:
                return App::isLocale('ar') ? 'صباحي' : 'Morning';
            case self::NIGHT:
                return App::isLocale('ar') ? 'مسائي' : 'Night';
            default:
                throw new \InvalidArgumentException("Invalid pharmacy type value: $value");
        }
    }
}
