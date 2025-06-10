<?php

namespace App\DTOs;

class CollectionLogsRequestDTO
{
    public function __construct(
        public readonly array $logs_request,
    ){}

    public function toArray(){
        return [
            'logs_request' => $this->logs_request,
        ];
    }
}
