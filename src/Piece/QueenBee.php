<?php

namespace App\Piece;

class QueenBee extends AbstractPiece
{
    public function isMoveValid(string $from, string $to): bool
    {
        $board = clone $this->board;
        $board->popTile($from);
        if ($from == $to) {
            $this->setErrorMessage('Tile must move');
            return false;
        } elseif (!$board->isPositionEmpty($to)) {
            $this->setErrorMessage('Tile not empty');
            return false;
        } elseif (!$board->slide($from, $to)) {
            $this->setErrorMessage('Tile must slide');
            return false;
        }

        return true;
    }
}
