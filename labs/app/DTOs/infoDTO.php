<?php

namespace App\DTOs;

class infoDTO
{
    public $phpinfo;

    public function __construct($phpinfo){
        $this->phpinfo = $phpinfo;
    }
}
