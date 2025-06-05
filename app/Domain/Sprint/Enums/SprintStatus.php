<?php

namespace App\Domain\Sprint\Enums;

enum SprintStatus: string
{
    case Ongoing = 'ongoing';
    case Completed = 'completed';
    case Running = 'running';
    case Pending = 'pending';

    public function label(): string
    {
        return match($this) {
            SprintStatus::Ongoing => 'Em andamento sempre',
            SprintStatus::Completed => 'Finalizado',
            SprintStatus::Running => 'Em andamento',
            SprintStatus::Pending => 'Pendente',
        };
    }
}
