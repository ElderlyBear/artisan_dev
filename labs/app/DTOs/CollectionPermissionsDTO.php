<?php

namespace App\DTOs;

class CollectionPermissionsDTO
{
    public function __construct(
        public readonly array $permissions,
    ){

    }

    public function toArray(){
        return [
            'permissions' => $this->permissions,
        ];
    }
}
