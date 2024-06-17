<?php

namespace Modules\Product\Enums;

use Illuminate\Support\Facades\App;

class BatchRemainingExpiry
{
    public const PROHIBITED = 0;

    public const LESS_THAN_TWO_MONTHS = 1;

    public const FROM_THREE_TO_SIX = 2;

    public const FROM_SEVEN_TO_TWELVE = 3;

    public const FROM_THIRTEEN_TO_EIGHTEEN = 4;

    public const FROM_NINETEEN_TO_TWENTY_FOUR = 5;

    public const FROM_TWENTY_FIVE_TO_THIRTY = 6;

    public const FROM_THIRTY_ONE_TO_THIRTY_SIX = 7;

    public static function all()
    {
        return App::isLocale('ar') ?
            [
                [
                    'name' => 'منتهي الصلاحية ومحظور البيع',
                    'value' => self::PROHIBITED,
                ],
                [
                    'name' => 'أقل من شهرين',
                    'value' => self::LESS_THAN_TWO_MONTHS,
                ],
                [
                    'name' => 'من شهر 3 الى شهر 6',
                    'value' => self::FROM_THREE_TO_SIX,
                ],
                [
                    'name' => 'من شهر 7 الى شهر 12',
                    'value' => self::FROM_SEVEN_TO_TWELVE,
                ],
                [
                    'name' => 'من شهر 13 الى شهر 18',
                    'value' => self::FROM_THIRTEEN_TO_EIGHTEEN,
                ],
                [
                    'name' => 'من شهر 19 الى شهر 24',
                    'value' => self::FROM_NINETEEN_TO_TWENTY_FOUR,
                ],
                [
                    'name' => 'من شهر 25 الى شهر 30',
                    'value' => self::FROM_TWENTY_FIVE_TO_THIRTY,
                ],
                [
                    'name' => 'من شهر 31 الى شهر 36',
                    'value' => self::FROM_THIRTY_ONE_TO_THIRTY_SIX,
                ]
            ]
            :
            [
                [
                    'name' => 'Expired and prohibited for sale',
                    'value' => self::PROHIBITED,
                ],
                [
                    'name' => 'Less than 2 months',
                    'value' => self::LESS_THAN_TWO_MONTHS,
                ],
                [
                    'name' => 'From 3 months to 6 months',
                    'value' => self::FROM_THREE_TO_SIX,
                ],
                [
                    'name' => 'From 7 months to 12 months',
                    'value' => self::FROM_SEVEN_TO_TWELVE,
                ],
                [
                    'name' => 'From 13 months to 18 months',
                    'value' => self::FROM_THIRTEEN_TO_EIGHTEEN,
                ],
                [
                    'name' => 'From 19 months to 24 months',
                    'value' => self::FROM_NINETEEN_TO_TWENTY_FOUR,
                ],
                [
                    'name' => 'From 25 months to 30 months',
                    'value' => self::FROM_TWENTY_FIVE_TO_THIRTY,
                ],
                [
                    'name' => 'From 31 months to 36 months',
                    'value' => self::FROM_THIRTY_ONE_TO_THIRTY_SIX,
                ]
            ];
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::PROHIBITED:
                return App::isLocale('ar') ? 'منتهي الصلاحية ومحظور البيع' : 'Expired and prohibited for sale';
            case self::LESS_THAN_TWO_MONTHS:
                return App::isLocale('ar') ? 'أقل من شهرين' : 'Less than 2 months';
            case self::FROM_THREE_TO_SIX:
                return App::isLocale('ar') ? 'من شهر 3 الى شهر 6' : 'From 3 months to 6 months';
            case self::FROM_SEVEN_TO_TWELVE:
                return App::isLocale('ar') ? 'من شهر 7 الى شهر 12' : 'From 7 months to 12 months';
            case self::FROM_THIRTEEN_TO_EIGHTEEN:
                return App::isLocale('ar') ? 'من شهر 13 الى شهر 18' : 'From 13 months to 18 months';
            case self::FROM_NINETEEN_TO_TWENTY_FOUR:
                return App::isLocale('ar') ? 'من شهر 19 الى شهر 24' : 'From 19 months to 24 months';
            case self::FROM_TWENTY_FIVE_TO_THIRTY:
                return App::isLocale('ar') ? 'من شهر 25 الى شهر 30' : 'From 25 months to 30 months';
            case self::FROM_THIRTY_ONE_TO_THIRTY_SIX:
                return App::isLocale('ar') ? 'من شهر 31 الى شهر 36' : 'From 31 months to 36 months';
            default:
                throw new \InvalidArgumentException("Invalid batch remaining expiry value: $value");
        }
    }
}
