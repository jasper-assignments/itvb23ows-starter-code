<?php

namespace App\Piece;

use App\Piece\AbstractPiece;

class Beetle extends AbstractPiece
{

    public function isMoveValid(string $from, string $to): bool
    {
        $board = clone $this->board;
        $board->popTile($from);
        if ($from == $to) {
            $this->setErrorMessage('Tile must move');
            return false;
        } elseif (!$board->slide($from, $to)) {
            $this->setErrorMessage('Tile must slide');
            return false;
        }
        return true;
    }
}
