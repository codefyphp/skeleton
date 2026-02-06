<?php

declare(strict_types=1);

namespace Domain\User\Enum;

enum UserRole: string
{
    case USER = 'user';
    case MANAGER = 'manager';
    case ADMIN = 'admin';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(array: self::cases(), column_key: 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::USER => 'User',
            self::MANAGER => 'Manager',
            self::ADMIN => 'Admin',
        };
    }
}
