<?php

namespace App\Piece;

use App\Entity\Board;
use App\Exception\PieceDoesNotExistException;
use Exception;

abstract class AbstractPiece
{
    /**
     * @throws Exception
     */
    public static function createFromLetter(string $letter, Board $board): AbstractPiece
    {
        return match ($letter) {
            'Q' => new QueenBee($board),
            'B' => new Beetle($board),
            'G' => new Grasshopper($board),
            'A' => new SoldierAnt($board),
            'S' => new Spider($board),
            default => throw new PieceDoesNotExistException($letter),
        };
    }

    protected Board $board;
    private ?string $errorMessage = null;

    public function __construct(Board $board)
    {
        $this->board = $board;
    }

    abstract public function isMoveValid(string $from, string $to): bool;

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    protected function setErrorMessage(string $errorMessage): void
    {
        $this->errorMessage = $errorMessage;
    }
}
