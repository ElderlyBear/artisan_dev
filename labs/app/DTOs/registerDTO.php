<?php

namespace App\DTOs;

class registerDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
    ){}

    public static function toArray(registerDTO $registerDTO){
        return new self(
            $registerDTO->name,
            $registerDTO->email,
            $registerDTO->password
        );
    }
}
