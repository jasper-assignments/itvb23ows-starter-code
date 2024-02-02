<?php

namespace App\Piece;

use App\Entity\Board;

class Spider extends AbstractPiece
{
    public function isMoveValid(string $from, string $to): bool
    {
        $board = clone $this->board;
        $board->popTile($from);

        if (!$board->isPositionEmpty($to)) {
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
        array $visited = [],
        int $i = 0
    ): bool {
        if ($current == $destination && $i <= 3) {
            return true;
        }

        $emptyNeighbours = $board->getNeighbourPositions($current, fn($neighbour) => $board->isPositionEmpty($neighbour));
        foreach ($emptyNeighbours as $neighbor) {
            if (in_array($neighbor, $visited)) {
                continue;
            }

            if ($board->slide($current, $neighbor)) {
                $visited[] = $current;
                if ($this->canDestinationBeReachedBySliding($board, $neighbor, $destination, $visited, $i + 1)) {
                    return true;
                }
            }
        }

        return false;
    }
}