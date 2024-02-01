<?php

use App\Entity\Hand;
use PHPUnit\Framework\TestCase;

class HandTest extends TestCase
{
    public function testAvailablePiecesDoesNotContainPieceWithZeroAvailable()
    {
        // arrange
        $hand = new Hand(['Q' => 1]);

        // act
        $hand->removePiece('Q');
        $availablePieces = $hand->getAvailablePieces();

        // assert
        $this->assertArrayNotHasKey('Q', $availablePieces);
    }

    public function testAvailablePiecesContainsPieceWithWithMoreThanZeroAvailable()
    {
        // arrange
        $hand = new Hand(['B' => 2]);

        // act
        $hand->removePiece('B');
        $availablePieces = $hand->getAvailablePieces();

        // assert
        $this->assertArrayHasKey('B', $availablePieces);
    }
}
