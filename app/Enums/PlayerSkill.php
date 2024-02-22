<?php
declare(strict_types=1);

namespace App\Enums;

enum PlayerSkill: string
{
    case Defence = 'defence';
    case Attack = 'attack';
    case Speed = 'speed';
    case Strength = 'strength';
    case Stamina = 'stamina';


    public static function arrayValues(): array
    {
        return [
            self::Defence->value,
            self::Attack->value,
            self::Speed->value,
            self::Strength->value,
            self::Stamina->value,
        ];
    }
 }
