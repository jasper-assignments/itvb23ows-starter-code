<?php

namespace App\Piece;

use App\Entity\Board;

class SoldierAnt extends AbstractPiece
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
        } elseif (!$this->canDestinationBeReachedBySliding($board, $from, $to)) {
            $this->setErrorMessage('Tile must slide');
            return false;
        }
        return true;
    }

    public function canDestinationBeReachedBySliding(
        Board $board,
        string $current,
        string $destination,
        string $lastVisited = null
    ): bool
    {
        if ($current == $destination) {
            return true;
        }

        $emptyNeighbours = $board->getNeighbourPositions($current, fn($neighbour) => $board->isPositionEmpty($neighbour));
        foreach ($emptyNeighbours as $neighbor) {
            if ($neighbor == $lastVisited) {
                continue;
            }

            if ($board->slide($current, $neighbor)) {
                if ($this->canDestinationBeReachedBySliding($board, $neighbor, $destination, $current)) {
                    return true;
                }
            }
        }

        return false;
    }
}
