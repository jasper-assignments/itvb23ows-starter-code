<?php

use App\Entity\Board;
use App\Piece\QueenBee;
use PHPUnit\Framework\TestCase;

class QueenBeeTest extends TestCase
{
    public function testSlideIsPossibleForQueenWithOnlyQueenAsNeighbour()
    {
        // arrange
        $board = new Board([
            '0,0' => [[0, 'Q']],
            '1,0' => [[1, 'Q']],
        ]);
        $queenBee = new QueenBee($board);
        $from = '0,0';
        $to = '0,1';

        // act
        $valid = $queenBee->isMoveValid($from, $to);

        // assert
        $this->assertTrue($valid);
    }

    public function testSlideIsNotPossibleForQueenWithOneOpenNeighbour()
    {
        // arrange
        $board = new Board([
            '1,-1' => [[0, 'B']],
            '2,-1' => [[0, 'Q']],
            '1,0' => [[1, 'Q']],
            '2,0' => [[0, 'B']],
            '0,1' => [[1, 'S']],
            '1,1' => [[1, 'B']],
        ]);
        $queenBee = new QueenBee($board);
        $from = '1,0';
        $to = '0,0';

        // act
        $valid = $queenBee->isMoveValid($from, $to);

        // assert
        $this->assertFalse($valid);
    }

    public function testSlideIsPossibleForQueenWithTwoOpenNeighbours()
    {
        // arrange
        $board = new Board([
            '2,-2' => [[0, 'B']],
            '2,-1' => [[0, 'Q']],
            '1,0' => [[1, 'Q']],
            '2,0' => [[0, 'B']],
            '0,1' => [[1, 'S']],
            '1,1' => [[1, 'B']],
        ]);
        $queenBee = new QueenBee($board);
        $from = '1,0';
        $to = '0,0';

        // act
        $valid = $queenBee->isMoveValid($from, $to);

        // assert
        $this->assertTrue($valid);
    }
}
