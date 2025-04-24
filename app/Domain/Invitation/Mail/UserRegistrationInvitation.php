<?php

declare(strict_types=1);

namespace App\Domain\Invitation\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Content, Envelope};
use Illuminate\Queue\SerializesModels;

class UserRegistrationInvitation extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected readonly string $urlRegister,
        protected readonly string $ownerTeamName,
        protected readonly int $teamId,
        protected readonly string $expirationDate,
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Convite para se cadastrar no time de {$this->ownerTeamName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.user-registration-invitation',
            with: [
                'urlRegister' => $this->urlRegister,
                'ownerTeamName' => $this->ownerTeamName,
                'teamId' => $this->teamId,
                'expirationDays' => $this->expirationDate,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
