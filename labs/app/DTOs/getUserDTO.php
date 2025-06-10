<?php

namespace App\DTOs;

class getUserDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
    ){}

    public static function toArray(getUserDTO $getUserDTO){
        return new self(
            $getUserDTO->name,
            $getUserDTO->email,
        );
    }
}
