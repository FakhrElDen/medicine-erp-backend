<?php

namespace Modules\Product\Enums;

class BatchStatus
{
    public const VALID = 0;

    public const PROHIBITED = 1;

    public const EXPIRED = 2;

    public static function all()
    {
        $data = [
            [
                'name' => 'valid',
                'value' => self::VALID,
            ],
            [
                'name' => 'prohibited',
                'value' => self::PROHIBITED,
            ],
            [
                'name' => 'expired',
                'value' => self::EXPIRED,
            ]
        ];

        return $data;
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::VALID:
                return 'valid';
            case self::PROHIBITED:
                return 'prohibited';
            case self::EXPIRED:
                return 'expired';
            default:
                throw new \InvalidArgumentException("Invalid batch status value: $value");
        }
    }
}
