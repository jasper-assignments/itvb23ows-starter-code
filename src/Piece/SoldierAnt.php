<?php

namespace App\Piece;

class SoldierAnt extends AbstractPiece
{

    public function isMoveValid(string $from, string $to): bool
    {
        if ($from == $to) {
            $this->setErrorMessage('Tile must move');
            return false;
        }
        return true;
    }
}