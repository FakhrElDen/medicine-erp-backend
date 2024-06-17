<?php

namespace Modules\Cart\Enums;

class CartStatus
{
    public const PENDING = 0;

    public const IN_PROGRESS = 1;

    public const COMPLETED = 2;

    public static function all()
    {
        return [
            'pending'       => self::PENDING,
            'in_progress'   => self::IN_PROGRESS,
            'completed'     => self::COMPLETED,
        ];
    }

    public static function getStringValue($value): string
    {
        switch ($value) {
            case self::PENDING:
                return 'pending';
            case self::IN_PROGRESS:
                return 'in_progress';
            case self::COMPLETED:
                return 'completed';
            default:
                throw new \InvalidArgumentException("Invalid cart type value: $value");
        }
    }
}
