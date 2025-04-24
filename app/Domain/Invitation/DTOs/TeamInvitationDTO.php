<?php

declare(strict_types=1);

namespace App\Domain\Invitation\DTOs;

use JsonSerializable;

final readonly class TeamInvitationDTO implements JsonSerializable
{
    /**
     * @param  non-empty-string  $email
     * @param  positive-int  $teamId
     * @param  non-empty-string  $billingType
     */
    public function __construct(
        public string $email,
        public int $teamId,
        public int $billingType,
        public int $billingRate,
        public int $costRate,
        public int $role,
    ) {}

    /**
     * Creates a new DTO instance from an array
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            email: (string) $data['email'],
            teamId: (int) $data['team_id'],
            billingType: (string) $data['billing_type'],
            billingRate: (int) $data['billing_rate'],
            costRate: (int) $data['cost_rate'],
            role: (int) $data['role'],
        );
    }

    /**
     * Converts the DTO to an array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'team_id' => $this->teamId,
            'billing_type' => $this->billingType,
            'billing_rate' => $this->billingRate,
            'cost_rate' => $this->costRate,
            'role' => $this->role,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
