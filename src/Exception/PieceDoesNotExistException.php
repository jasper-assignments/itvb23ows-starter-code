<?php

namespace App\Exception;

class PieceDoesNotExistException extends \Exception
{
    public function __construct(string $letter)
    {
        parent::__construct("Piece with letter '$letter' does not exist");
    }
}
