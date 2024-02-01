<?php

namespace App\Piece;

use App\Entity\Board;
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
            'A' => new SoldierAnt($board),
            default => throw new Exception("Piece with letter '$letter' does not exist"),
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
