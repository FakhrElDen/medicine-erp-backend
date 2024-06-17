<?php

namespace Modules\Setting\Enums;

class SettingType
{
    public const STRING = 0;

    public const INTEGER = 1;

    public const JSON = 2;

    public const FILE = 3;

    public static function all()
    {
        return [
            'string'    => self::STRING,
            'integer'   => self::INTEGER,
            'json'      => self::JSON,
            'file'      => self::FILE,
        ];
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::STRING:
                return 'string';
            case self::INTEGER:
                return 'integer';
            case self::JSON:
                return 'json';
            case self::FILE:
                return 'file';
            default:
                throw new \InvalidArgumentException("Invalid setting type value: $value");
        }
    }
}
