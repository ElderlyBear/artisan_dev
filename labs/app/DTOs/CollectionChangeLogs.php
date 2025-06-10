<?php

namespace App\DTOs;

class CollectionChangeLogs
{
    public function __construct(
        public readonly array $change_logs,
    ){

    }
}
