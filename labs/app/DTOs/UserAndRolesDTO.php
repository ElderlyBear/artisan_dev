<?php

namespace App\DTOs;

class UserAndRolesDTO
{
    public function __construct(
        public readonly int $user_id,
        public readonly int $role_id,
    ){}
}
