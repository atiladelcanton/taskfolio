<?php

namespace App\Enums;

enum TaskStatusEnum: int
{
    case BACKLOG = 1;
    case EM_ANDAMENTO = 2;
    case VALIDACAO = 3;
    case CORRECAO = 4;
    case CONCLUIDO = 5;

    public static function fromCode(int $code): string
    {
        return match ($code) {
            self::BACKLOG->value => 'Backlog',
            self::EM_ANDAMENTO->value => 'Em andamento',
            self::VALIDACAO->value => 'Validação',
            self::CORRECAO->value => 'Correção',
            self::CONCLUIDO->value => 'Concluído',
            default => 'Backlog',
        };
    }
}
