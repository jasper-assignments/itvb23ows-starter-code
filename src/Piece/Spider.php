<?php

namespace App\Piece;

class Spider extends AbstractPiece
{
    public function isMoveValid(string $from, string $to): bool
    {
        $board = clone $this->board;
        $board->popTile($from);

        if (!$board->isPositionEmpty($to)) {
            return false;
        }
        return true;
    }
}