<?php

declare(strict_types=1);

namespace Domain\User\Enum;

enum UserRole: string
{
    case USER = 'user';
    case MANAGER = 'manager';
    case ADMIN = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::USER => 'User',
            self::MANAGER => 'Manager',
            self::ADMIN => 'Admin',
        };
    }
}
