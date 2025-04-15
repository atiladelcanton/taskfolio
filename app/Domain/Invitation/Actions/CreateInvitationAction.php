<?php

namespace App\Domain\Invitation\Actions;

use App\Domain\Invitation\DTOs\TeamInvitationDTO;
use App\Domain\Invitation\Models\TeamInvitation;
use Ramsey\Uuid\Uuid;

class CreateInvitationAction
{
    public static function execute(TeamInvitationDTO $invitationDTO):void{
        $invitationCode = Uuid::uuid4()->toString();
        $now = now();
        $expiresAt = $now->modify('+7 days');
        TeamInvitation::query()->create([
            'email' => $invitationDTO->email,
            'team_id' => $invitationDTO->teamId,
            'invitation_code' => $invitationCode,
            'expires_at' => $expiresAt,
            'billing_type' => $invitationDTO->billingType,
            'billing_rate' => $invitationDTO->billingRate,
            'cost_rate' => $invitationDTO->costRate,
            'status' => 1
        ]);
    }
}
