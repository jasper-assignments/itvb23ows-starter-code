<?php

namespace App\Piece;

use App\Entity\Board;

class Spider extends AbstractPiece
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
        } elseif (!$this->canDestinationBeReachedBySlidingThreeTimes($board, $from, $to)) {
            $this->setErrorMessage('Tile must slide exactly 3 times');
            return false;
        }
        return true;
    }

    public function canDestinationBeReachedBySlidingThreeTimes(
        Board $board,
        string $current,
        string $destination,
        array $visited = [],
        int $i = 0
    ): bool {
        if ($current == $destination && $i == 3) {
            return true;
        }

        $emptyNeighbours = $board->getNeighbourPositions(
            $current,
            fn($neighbour) => $board->isPositionEmpty($neighbour)
        );
        foreach ($emptyNeighbours as $neighbor) {
            if (in_array($neighbor, $visited)) {
                continue;
            }

            if ($board->slide($current, $neighbor)) {
                $visited[] = $current;
                if (
                    $this->canDestinationBeReachedBySlidingThreeTimes(
                        $board,
                        $neighbor,
                        $destination,
                        $visited,
                        $i + 1
                    )
                ) {
                    return true;
                }
            }
        }

        return false;
    }
}