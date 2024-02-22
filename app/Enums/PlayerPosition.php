<?php
declare(strict_types=1);

namespace App\Enums;

enum PlayerPosition: string
{
    case Defender = 'defender';
    case Midfielder = 'midfielder';
    case Forward = 'forward';

    public static function arrayValues(): array
    {
        return [
            self::Defender->value,
            self::Midfielder->value,
            self::Forward->value,
        ];
    }
}
