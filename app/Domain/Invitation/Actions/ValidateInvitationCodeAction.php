<?php

declare(strict_types=1);

namespace App\Domain\Invitation\Actions;

use App\Domain\Invitation\Models\TeamInvitation;

class ValidateInvitationCodeAction
{
    public static function validate(): bool
    {
        if (! request()->has('token') && ! request()->has('email')) {
            return false;
        }
        $registrationCode = request()->get('token');
        $email = request()->get('email');
        return TeamInvitation::query()->where('invitation_code', $registrationCode)->where('email', $email)->exists();
    }
}
