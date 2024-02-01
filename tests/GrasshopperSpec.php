<?php

use App\Entity\Board;
use App\Piece\Grasshopper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GrasshopperSpec extends TestCase
{
    #[Test]
    public function givenStraightDestinationOnXAxisThenMoveValidIsTrue()
    {
        // arrange
        $board = new Board([
            '0,0' => [[0, 'G']]
        ]);
        $grasshopper = new Grasshopper($board);
        $from = '0,0';
        $to = '0,2';

        // act
        $valid = $grasshopper->isMoveValid($from, $to);

        // assert
        $this->assertTrue($valid);
    }

    #[Test]
    public function givenStraightDestinationOnYAxisThenMoveValidIsTrue()
    {
        // arrange
        $board = new Board([
            '0,0' => [[0, 'G']]
        ]);
        $grasshopper = new Grasshopper($board);
        $from = '0,0';
        $to = '2,0';

        // act
        $valid = $grasshopper->isMoveValid($from, $to);

        // assert
        $this->assertTrue($valid);
    }

    #[Test]
    public function givenNonStraightDestinationThenMoveValidIsFalse()
    {
        // arrange
        $board = new Board([
            '0,0' => [[0, 'G']]
        ]);
        $grasshopper = new Grasshopper($board);
        $from = '0,0';
        $to = '3,-2';

        // act
        $valid = $grasshopper->isMoveValid($from, $to);

        // assert
        $this->assertFalse($valid);
    }

    #[Test]
    public function givenStraightDestinationOnUpwardDiagonalThenMoveValidIsTrue()
    {
        // arrange
        $board = new Board([
            '0,0' => [[0, 'G']]
        ]);
        $grasshopper = new Grasshopper($board);
        $from = '0,0';
        $to = '2,2';

        // act
        $valid = $grasshopper->isMoveValid($from, $to);

        // assert
        $this->assertTrue($valid);
    }

    #[Test]
    public function givenStraightDestinationOnDownwardDiagonalThenMoveValidIsTrue()
    {
        // arrange
        $board = new Board([
            '0,0' => [[0, 'G']]
        ]);
        $grasshopper = new Grasshopper($board);
        $from = '0,0';
        $to = '2,-2';

        // act
        $valid = $grasshopper->isMoveValid($from, $to);

        // assert
        $this->assertTrue($valid);
    }

    #[Test]
    public function givenEmptyPlacesBeforeDestinationThenMoveValidIsFalse()
    {
        // arrange
        $board = new Board([
            '0,0' => [[0, 'G']]
        ]);
        $grasshopper = new Grasshopper($board);
        $from = '0,0';
        $to = '2,-2';

        // act
        $valid = $grasshopper->isMoveValid($from, $to);

        // assert
        $this->assertFalse($valid);
    }
}
