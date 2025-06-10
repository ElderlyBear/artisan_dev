<?php

namespace App\DTOs;

class LogsRequestDTO
{
    public function __construct(
        public readonly string $address,
        public readonly string $method,
        public readonly string $controller_path,
        public readonly string $controller_method,
        public readonly string $body_of_request,
        public readonly string $request_headers,
        public readonly string $identifier,
        public readonly string $ip_address,
        public readonly string $user_agent,
        public readonly string $status,
        public readonly string $body_of_response,
        public readonly string $response_headers,
    ){}
}
