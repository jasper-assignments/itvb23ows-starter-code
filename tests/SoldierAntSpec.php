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
            '0,0' => [[0, 'S']],
        ]);
        $soldierAnt = new SoldierAnt($board);
        $from = '0,0';
        $to = '0,1';

        // act
        $valid = $soldierAnt->isMoveValid($from, $to);

        // assert
        $this->assertTrue($valid);
    }
}
