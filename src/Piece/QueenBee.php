<?php

namespace App\Piece;

class QueenBee extends AbstractPiece
{
    public function isMoveValid(string $from, string $to): bool
    {
        if ($from == $to) {
            $this->setErrorMessage('Tile must move');
            return false;
        } elseif (!$this->board->isPositionEmpty($to)) {
            $this->setErrorMessage('Tile not empty');
            return false;
        } elseif (!$this->board->slide($from, $to)) {
            $this->setErrorMessage('Tile must slide');
            return false;
        }

        return true;
    }
}
