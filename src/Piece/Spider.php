<?php

namespace App\Piece;

class Spider extends AbstractPiece
{
    public function isMoveValid(string $from, string $to): bool
    {
        return true;
    }
}