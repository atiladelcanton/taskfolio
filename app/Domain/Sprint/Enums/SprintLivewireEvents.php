<?php

namespace App\Domain\Sprint\Enums;

enum SprintLivewireEvents: string
{
    case EditModal = 'edit-sprint-modal';
    case ClearModal = 'clear-sprint-modal';
    case Remove = 'remove-sprint';
}
