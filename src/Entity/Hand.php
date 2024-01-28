<?php

namespace App\Entity;

class Hand
{
    private array $pieces = [
        "Q" => 1,
        "B" => 2,
        "S" => 2,
        "A" => 3,
        "G" => 3,
    ];

    public function __construct(array $pieces = null)
    {
        if (isset($pieces)) {
            $this->pieces = $pieces;
        }
    }

    public function getPieces(): array
    {
        return $this->pieces;
    }

    public function hasPiece(string $piece): bool
    {
        return $this->pieces[$piece] > 0;
    }

    public function removePiece(string $piece): void
    {
        $this->pieces[$piece]--;
    }

    public function getTotalSum(): int
    {
        return array_sum($this->pieces);
    }
}
