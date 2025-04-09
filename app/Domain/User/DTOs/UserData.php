<?php

declare(strict_types=1);

namespace App\Domain\User\DTOs;

/**
 * @phpstan-type UserDataArray array{
 *     name: string,
 *     email: string,
 *     password: string
 * }
 */
readonly class UserData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {}

    /**
     * @param UserDataArray $data
     */
    public static function from(array $data): self
    {
        return new self(
            $data['name'],
            $data['email'],
            $data['password'],
        );
    }

    /**
     * @return array{
     *     name: string,
     *     email: string,
     *     password: string
     * }
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
