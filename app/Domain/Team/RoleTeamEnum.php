<?php

declare(strict_types=1);

namespace App\Domain\Team;

enum RoleTeamEnum: int
{
    case Owner = 1;
    case Member = 2;
    case Client = 3;

    public static function getRolesName(int $role): string
    {
        return match ($role) {
            self::Owner->value => 'Dono',
            self::Member->value => 'Colaborador',
            self::Client->value => 'Cliente',
            default => '',
        };
    }

    public static function getRoles(): array
    {
        return [
            [self::Member->value, 'Colaborador'], [self::Client->value, 'Cliente'],
        ];
    }
}
