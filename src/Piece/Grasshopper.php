<?php

namespace App\Piece;

class Grasshopper extends AbstractPiece
{
    public function isMoveValid(string $from, string $to): bool
    {
        if (!$this->isMoveStraight($from, $to)) {
            return false;
        }

        return true;
    }

    public function isMoveStraight(string $from, string $to): bool
    {
        [$fromX, $fromY] = explode(',', $from);
        [$toX, $toY] = explode(',', $to);

        // Check if line is straight on X or Y axis
        if ($fromX == $toX || $fromY == $toY) {
            return true;
        }

        // Check if line is straight on upward and downward diagonal
        if (abs($fromX - $toX) == abs($fromY - $toY)) {
            return true;
        }

        return false;
    }
}