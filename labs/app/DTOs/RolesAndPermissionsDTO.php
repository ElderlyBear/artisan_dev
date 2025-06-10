<?php

namespace App\DTOs;

class RolesAndPermissionsDTO
{
    public function __construct(
        public readonly int $role_id,
        public readonly int $permission_id,
    )
    {

    }
}
