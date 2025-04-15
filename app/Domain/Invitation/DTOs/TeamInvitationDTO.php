<?php

namespace App\Domain\Invitation\DTOs;
use JsonSerializable;


final readonly class TeamInvitationDTO implements JsonSerializable
{
    /**
     * @param non-empty-string $email
     * @param positive-int $teamId
     * @param non-empty-string $billingType
     * @param int $billingRate
     */
    public function __construct(
        public string $email,
        public int $teamId,
        public string $billingType,
        public int $billingRate,
        public int $costRate,
    ) {}

    /**
     * Creates a new DTO instance from an array
     *
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            email: (string) $data['email'],
            teamId: (int) $data['team_id'],
            billingType: (string) $data['billing_type'],
            billingRate: (int) $data['billing_rate'],costRate:(int) $data['cost_rate'],
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
