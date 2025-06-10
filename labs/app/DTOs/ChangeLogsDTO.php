<?php

namespace App\DTOs;

class ChangeLogsDTO
{
    public function __construct(
        public readonly string $entity_type,
        public readonly int $entity_id,
        public readonly ?array $before,
        public readonly ?array $after,
    ){}
}
