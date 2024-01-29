<?php

namespace App\Exception;

use Exception;

class InvalidMoveException extends Exception
{
    public function __construct(string $message = 'Invalid move')
    {
        parent::__construct($message);
    }
}
