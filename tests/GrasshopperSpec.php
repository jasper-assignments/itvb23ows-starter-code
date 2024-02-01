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
            '0,0' => [[0, 'G']],
            '0,1' => [[1, 'Q']],
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
            '0,0' => [[0, 'G']],
            '1,0' => [[1, 'Q']],
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
            '0,0' => [[0, 'G']],
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
            '0,0' => [[0, 'G']],
            '1,1' => [[1, 'Q']],
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
            '0,0' => [[0, 'G']],
            '1,-1' => [[1, 'Q']],
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

    #[Test]
    public function givenOccupiedPlacesBeforeDestinationThenMoveValidIsTrue()
    {
        // arrange
        $board = new Board([
            '0,0' => [[0, 'G']],
            '1,-1' => [[1, 'B']],
            '2,-2' => [[0, 'S']],
        ]);
        $grasshopper = new Grasshopper($board);
        $from = '0,0';
        $to = '3,-3';

        // act
        $valid = $grasshopper->isMoveValid($from, $to);

        // assert
        $this->assertTrue($valid);
    }

    #[Test]
    public function givenCurrentPlaceAsDestinationThenMoveValidIsFalse()
    {
        // arrange
        $board = new Board([
            '0,0' => [[0, 'G']]
        ]);
        $grasshopper = new Grasshopper($board);
        $from = '0,0';
        $to = '0,0';

        // act
        $valid = $grasshopper->isMoveValid($from, $to);

        // assert
        $this->assertFalse($valid);
    }

    #[Test]
    public function givenOccupiedPositionAsDestinationThenMoveValidIsFalse()
    {
        // arrange
        $board = new Board([
            '0,0' => [[0, 'G']],
            '1,1' => [[1, 'Q']],
            '2,2' => [[1, 'B']],
        ]);
        $grasshopper = new Grasshopper($board);
        $from = '0,0';
        $to = '2,2';

        // act
        $valid = $grasshopper->isMoveValid($from, $to);

        // assert
        $this->assertFalse($valid);
    }

    #[Test]
    public function givenPositionNextToCurrentPositionThenMoveValidIsFalse()
    {
        // arrange
        $board = new Board([
            '0,0' => [[0, 'G']],
        ]);
        $grasshopper = new Grasshopper($board);
        $from = '0,0';
        $to = '1,1';

        // act
        $valid = $grasshopper->isMoveValid($from, $to);

        // assert
        $this->assertFalse($valid);
    }
}
