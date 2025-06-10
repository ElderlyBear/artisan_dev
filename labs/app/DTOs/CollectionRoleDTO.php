<?php

namespace App\DTOs;

class CollectionRoleDTO
{
    public function __construct(
        public readonly array $roles,
    ){}

    public function toArray(){
        return [
            'roles' => $this->roles,
        ];

    }
}
