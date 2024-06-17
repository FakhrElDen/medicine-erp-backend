<?php

namespace Modules\Client\Enums;

use Illuminate\Support\Facades\App;

class PharmacyActive
{
    public const VISIBLE = 0;

    public const INVISIBLE = 1;

    public static function all()
    {
        $data = [
            [
                'name' => 'Visible',
                'value' => self::VISIBLE,
            ],
            [
                'name' => 'Invisible',
                'value' => self::INVISIBLE,
            ],
        ];

        return $data;
    }

    public static function getStringValue($value)
    {
        switch ($value) {
            case self::VISIBLE:
                return App::isLocale('ar') ? 'ظاهر' : 'Visible';
            case self::INVISIBLE:
                return App::isLocale('ar') ? 'مخفي' : 'Invisible';
            default:
                throw new \InvalidArgumentException("Invalid pharmacy active value: $value");
        }
    }
}
