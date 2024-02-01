<?php

use App\Entity\Board;
use PHPUnit\Framework\TestCase;

class BoardSpec extends TestCase
{
    public function givenGrasshopperStraightDestinationThenMoveValidIsTrue(): void
    {
        // arrange
        $board = new Board();
        $from = '0,0';
        $to = '2,0';

        // act
        $valid = $board->isGrasshopperMoveValid($from, $to);

        // assert
        $this->assertTrue($valid);
    }
}