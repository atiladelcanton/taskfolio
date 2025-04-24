<?php

declare(strict_types=1);

namespace App\Domain\Invitation\Listeners;

use App\Events\UserProjectInvitationEvent;

class SendProjectInvitationEmailListener
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
    public function handle(UserProjectInvitationEvent $event): void
    {
        //
    }
}
