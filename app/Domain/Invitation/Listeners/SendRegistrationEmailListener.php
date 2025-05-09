<?php

declare(strict_types=1);

namespace App\Domain\Invitation\Listeners;

use App\Domain\Invitation\Events\UserRegistrationInvitationEvent;
use App\Domain\Invitation\Mail\UserRegistrationInvitation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class SendRegistrationEmailListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistrationInvitationEvent $event): void
    {

        $registrationUrl = URL::signedRoute('register-invitation',['token' => $event->invitation->invitation_code, 'email' => $event->invitation->email],now()->addDays(7) ,false);

        $expirationDate = Carbon::createFromTimestamp($event->invitation->expires_at)->format('d/m/Y');
        Mail::to($event->invitation->email)
            ->send(new UserRegistrationInvitation(
                urlRegister: $registrationUrl,
                ownerTeamName: $event->invitation->team->owner->name,
                teamId: $event->invitation->team_id,
                expirationDate: $expirationDate
            ));
    }
}
