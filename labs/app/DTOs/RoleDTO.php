<?php

namespace App\DTOs;

class RoleDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly string $code,
    )
    {
    }

    public function toArray(RoleDTO $roleDTO){
        return new self(
            $roleDTO->name,
            $roleDTO->description,
            $roleDTO->code,
        );
    }
}
