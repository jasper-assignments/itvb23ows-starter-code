<?php

use App\Entity\Board;
use App\Piece\SoldierAnt;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SoldierAntSpec extends TestCase
{
    #[Test]
    public function givenDestinationNextToCurrentPositionThenMoveValidIsTrue()
    {
        // arrange
        $board = new Board([
            '1,-1' => [[0, 'Q']],
            '0,0' => [[0, 'A']],
            '1,0' => [[1, 'Q']],
        ]);
        $soldierAnt = new SoldierAnt($board);
        $from = '0,0';
        $to = '0,1';

        // act
        $valid = $soldierAnt->isMoveValid($from, $to);

        // assert
        $this->assertTrue($valid);
    }

    #[Test]
    public function givenCurrentPositionAsDestinationThenMoveValidIsFalse()
    {
        // arrange
        $board = new Board([
            '0,0' => [[0, 'A']],
        ]);
        $soldierAnt = new SoldierAnt($board);
        $from = '0,0';
        $to = '0,0';

        // act
        $valid = $soldierAnt->isMoveValid($from, $to);

        // assert
        $this->assertFalse($valid);
    }

    #[Test]
    public function givenOccupiedPositionAsDestinationThenMoveValidIsFalse()
    {
        // arrange
        $board = new Board([
            '0,0' => [[0, 'A']],
            '0,1' => [[1, 'Q']],
        ]);
        $soldierAnt = new SoldierAnt($board);
        $from = '0,0';
        $to = '0,1';

        // act
        $valid = $soldierAnt->isMoveValid($from, $to);

        // assert
        $this->assertFalse($valid);
    }

    #[Test]
    public function givenEmptyPositionAsDestinationThenMoveValidIsTrue()
    {
        // arrange
        $board = new Board([
            '0,0' => [[0, 'A']],
            '0,1' => [[1, 'Q']],
        ]);
        $soldierAnt = new SoldierAnt($board);
        $from = '0,0';
        $to = '1,0';

        // act
        $valid = $soldierAnt->isMoveValid($from, $to);

        // assert
        $this->assertTrue($valid);
    }

    #[Test]
    public function givenPositionThatCannotBeSlidOutOfThenMoveValidIsFalse()
    {
        // arrange
        $board = new Board([
            '1,-1' => [[0, 'B']],
            '2,-1' => [[0, 'Q']],
            '1,0' => [[1, 'A']],
            '2,0' => [[0, 'B']],
            '0,1' => [[1, 'S']],
            '1,1' => [[1, 'B']],
        ]);
        $soldierAnt = new SoldierAnt($board);
        $from = '1,0';
        $to = '0,0';

        // act
        $valid = $soldierAnt->isMoveValid($from, $to);

        // assert
        $this->assertFalse($valid);
    }

    #[Test]
    public function givenDestinationThatIsMultipleSlidesAwayThenMoveValidIsTrue()
    {
        // arrange
        $board = new Board([
            '1,-1' => [[0, 'Q']],
            '0,0' => [[0, 'A']],
            '1,0' => [[1, 'Q']],
        ]);
        $soldierAnt = new SoldierAnt($board);
        $from = '0,0';
        $to = '2,-1';

        // act
        $valid = $soldierAnt->isMoveValid($from, $to);

        // assert
        $this->assertTrue($valid);
    }
}
