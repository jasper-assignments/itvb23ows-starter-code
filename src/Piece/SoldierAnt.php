<?php

namespace App\Piece;

class SoldierAnt extends AbstractPiece
{

    public function isMoveValid(string $from, string $to): bool
    {
        return true;
    }
}