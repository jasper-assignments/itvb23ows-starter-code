<?php

use App\Entity\Board;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class BoardSpec extends TestCase
{
    #[Test]
    public function givenGrasshopperStraightDestinationOnXAxisThenMoveValidIsTrue()
    {
        // arrange
        $board = new Board();
        $from = '0,0';
        $to = '0,2';

        // act
        $valid = $board->isGrasshopperMoveValid($from, $to);

        // assert
        $this->assertTrue($valid);
    }

    #[Test]
    public function givenGrasshopperStraightDestinationOnYAxisThenMoveValidIsTrue()
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

    #[Test]
    public function givenGrasshopperNonStraightDestinationThenMoveValidIsFalse()
    {
        // arrange
        $board = new Board();
        $from = '0,0';
        $to = '3,-2';

        // act
        $valid = $board->isGrasshopperMoveValid($from, $to);

        // assert
        $this->assertFalse($valid);
    }

    #[Test]
    public function givenGrasshopperStraightDestinationOnUpwardDiagonalThenMoveValidIsTrue()
    {
        // arrange
        $board = new Board();
        $from = '0,0';
        $to = '2,2';

        // act
        $valid = $board->isGrasshopperMoveValid($from, $to);

        // assert
        $this->assertTrue($valid);
    }
}