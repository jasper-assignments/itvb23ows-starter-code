<?php

use App\Entity\Board;
use App\Entity\Database;
use App\Entity\Game;
use App\Entity\Hand;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GameSpec extends TestCase
{
    #[Test]
    public function givenNoValidPlayOrMovePositionsThenCanPassIsTrue()
    {
        // arrange
        $databaseMock = Mockery::mock(Database::class);
        $board = new Board();
        $hands = [
            0 => new Hand([]),
            1 => new Hand([]),
        ];
        $currentPlayer = 0;
        $game = new Game($databaseMock, -1, $board, $hands, $currentPlayer);

        // act
        $canPass = $game->canPass();

        // assert
        $this->assertTrue($canPass);
    }
}