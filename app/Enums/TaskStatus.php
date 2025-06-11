<?php

declare(strict_types=1);

namespace App\Enums;

enum TaskStatus: string
{
    case TO_DO = 'to_do';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';

    public function label(): string
    {
        return match ($this) {
            self::TO_DO => __('К выполнению'),
            self::IN_PROGRESS => __('В работе'),
            self::DONE => __('Выполнено'),
        };
    }

}
