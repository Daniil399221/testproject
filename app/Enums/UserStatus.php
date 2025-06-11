<?php

declare(strict_types=1);

namespace App\Enums;

enum UserStatus: string
{
    case WORKING = 'working';
    case ON_VACATION = 'on_vacation';

    public function label(): string
    {
        return match ($this) {
            self::WORKING => __('Работает'),
            self::ON_VACATION => __('В отпуске'),
        };
    }

}
