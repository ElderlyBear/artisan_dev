<?php

namespace App\DTOs;

class loginDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password
    ){}

    public static function toArray(loginDTO $loginDTO){
        return new self(
            $loginDTO->email,
            $loginDTO->password
        );
    }
}
