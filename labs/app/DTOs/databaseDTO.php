<?php

namespace App\DTOs;

class databaseDTO
{

    public $database;

    public function __construct($database)
    {
        $this->database = $database;
    }
}
