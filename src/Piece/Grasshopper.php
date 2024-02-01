<?php

namespace App\Piece;

class Grasshopper extends AbstractPiece
{
    public function isMoveValid(string $from, string $to): bool
    {
        if ($from == $to) {
            $this->setErrorMessage('Tile must move');
            return false;
        }elseif (!$this->isMoveStraight($from, $to)) {
            $this->setErrorMessage('Move is not straight');
            return false;
        } elseif (!$this->areAllPositionsBetweenOccupied($from, $to)) {
            $this->setErrorMessage('Move jumps over empty positions');
            return false;
        }

        return true;
    }

    private function isMoveStraight(string $from, string $to): bool
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

    private function areAllPositionsBetweenOccupied(string $from, string $to): bool
    {
        $board = clone $this->board;
        $board->popTile($from);

        $positions = $this->getCoordinatesBetween($from, $to);
        foreach ($positions as $pos) {
            if ($board->isPositionEmpty($pos)) {
                return false;
            }
        }

        return true;
    }

    private function getCoordinatesBetween(string $start, string $end): array
    {
        [$startX, $startY] = explode(',', $start);
        [$endX, $endY] = explode(',', $end);

        $coordinates = [];
        if ($startY == $endY) { // Horizontal
            for ($x = min($startX, $endX) + 1; $x < max($startX, $endX); $x++) {
                $coordinates[] = "$x,$startY";
            }
        } elseif ($startX == $endX) { // Vertical
            for ($y = min($startY, $endY) + 1; $y < max($startY, $endY); $y++) {
                $coordinates[] = "$startX,$y";
            }
        } else {
            $slope = ($endY - $startY) / ($endX - $startX);
            $intercept = $startY - $slope * $startX;
            for ($x = $startX + 1; $x < $endX; $x++) {
                $y = $slope * $x + $intercept;
                $coordinates[] = "$x,$y";
            }
        }

        return $coordinates;
    }
}