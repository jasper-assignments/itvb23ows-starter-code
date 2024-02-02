<?php

use App\Entity\Board;
use App\Piece\Spider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SpiderSpec extends TestCase
{
    #[Test]
    public function givenDestinationExactlyThreeStepsAwayThenMoveValidIsTrue()
    {
        // arrange
        $board = new Board([
            '1,-1' => [[0, 'S']],
            '0,0' => [[0, 'Q']],
            '1,0' => [[1, 'Q']],
        ]);
        $spider = new Spider($board);
        $from = '1,-1';
        $to = '-1,1';

        // act
        $valid = $spider->isMoveValid($from, $to);

        // assert
        $this->assertTrue($valid);
    }

    #[Test]
    public function givenDestinationMoreThanThreeStepsAwayThenMoveValidIsFalse()
    {
        // arrange
        $board = new Board([
            '1,-1' => [[0, 'S']],
            '0,0' => [[0, 'Q']],
            '1,0' => [[1, 'Q']],
        ]);
        $spider = new Spider($board);
        $from = '1,-1';
        $to = '0,1';

        // act
        $valid = $spider->isMoveValid($from, $to);

        // assert
        $this->assertFalse($valid);
    }

    #[Test]
    public function givenDestinationLessThanThreeStepsAwayThenMoveValidIsFalse()
    {
        // arrange
        $board = new Board([
            '1,-1' => [[0, 'S']],
            '0,0' => [[0, 'Q']],
            '1,0' => [[1, 'Q']],
        ]);
        $spider = new Spider($board);
        $from = '1,-1';
        $to = '-1,0';

        // act
        $valid = $spider->isMoveValid($from, $to);

        // assert
        $this->assertFalse($valid);
    }

    #[Test]
    public function givenOccupiedPositionAsDestinationThenMoveValidIsFalse()
    {
        // arrange
        $board = new Board([
            '1,-1' => [[0, 'S']],
            '0,0' => [[0, 'Q']],
            '1,0' => [[1, 'Q']],
            '-1,1' => [[1, 'B']],
        ]);
        $spider = new Spider($board);
        $from = '0,0';
        $to = '-1,1';

        // act
        $valid = $spider->isMoveValid($from, $to);

        // assert
        $this->assertFalse($valid);
    }

    #[Test]
    public function givenEmptyPositionAsDestinationThenMoveValidIsTrue()
    {
        // arrange
        $board = new Board([
            '1,-1' => [[0, 'S']],
            '0,0' => [[0, 'Q']],
            '0,1' => [[1, 'Q']],
            '-1,1' => [[1, 'B']],
        ]);
        $spider = new Spider($board);
        $from = '1,-1';
        $to = '0,2';

        // act
        $valid = $spider->isMoveValid($from, $to);

        // assert
        $this->assertTrue($valid);
    }
}