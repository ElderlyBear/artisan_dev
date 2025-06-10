<?php

namespace App\DTOs;

class PermissionsDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly string $code
    ){}

    public function toArray(PermissionsDTO $permissionsDTO){
        return new self(
            $permissionsDTO->name,
            $permissionsDTO->description,
            $permissionsDTO->code,
        );
    }
}
