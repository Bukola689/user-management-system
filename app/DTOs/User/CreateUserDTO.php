<?php

namespace App\DTOs\User;

class CreateUserDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly ? string $phone = null,
    )
    {}
}
