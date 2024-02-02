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
    public function givenEmptyHandAndEmptyBoardThenCanPassIsTrue()
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

    #[Test]
    public function givenFullHandAndEmptyBoardThenCanPassIsFalse()
    {
        // arrange
        $databaseMock = Mockery::mock(Database::class);
        $board = new Board();
        $hands = [
            0 => new Hand(),
            1 => new Hand(),
        ];
        $currentPlayer = 0;
        $game = new Game($databaseMock, -1, $board, $hands, $currentPlayer);

        // act
        $canPass = $game->canPass();

        // assert
        $this->assertFalse($canPass);
    }

    #[Test]
    public function givenOnlyValidMoveThenCanPassIsFalse()
    {
        // arrange
        $databaseMock = Mockery::mock(Database::class);
        $board = new Board([
            '0,0' => [[0, 'Q']],
            '1,0' => [[1, 'Q']],
        ]);
        $hands = [
            0 => new Hand([
                'Q' => 0,
            ]),
            1 => new Hand([
                'Q' => 0,
            ]),
        ];
        $currentPlayer = 0;
        $game = new Game($databaseMock, -1, $board, $hands, $currentPlayer);

        // act
        $canPass = $game->canPass();

        // assert
        $this->assertFalse($canPass);
    }
}