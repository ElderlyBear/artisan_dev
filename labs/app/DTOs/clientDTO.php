<?php

namespace App\DTOs;

class clientDTO
{

    public $ip;
    public $useragent;

    public function __construct($ip, $useragent)
    {
        $this->ip = $ip;
        $this->useragent = $useragent;
    }


}
