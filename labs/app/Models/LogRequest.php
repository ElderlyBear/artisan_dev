<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogRequest extends Model
{
    protected $table = 'logs_requests';

    protected $fillable = [
        'address',
        'method',
        'controller_path',
        'controller_method',
        'body_of_request',
        'request_headers',
        'identifier',
        'ip_address',
        'user_agent',
        'status',
        'body_of_response',
        'response_headers',
    ];
}
