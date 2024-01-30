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

        // assert
        $this->assertArrayNotHasKey('Q', $hand->getAvailablePieces());
    }

    public function testAvailablePiecesContainsPieceWithWithMoreThanZeroAvailable()
    {
        // arrange
        $hand = new Hand(['B' => 2]);

        // act
        $hand->removePiece('B');

        // assert
        $this->assertArrayHasKey('B', $hand->getAvailablePieces());
    }
}
