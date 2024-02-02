<?php

use App\Entity\Ai;
use App\Entity\Board;
use App\Entity\Database;
use App\Entity\Game;
use App\Entity\Hand;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GameSpec extends TestCase
{
    use MockeryPHPUnitIntegration;

    #[Test]
    public function givenNoValidPlayAndNoValidMoveThenCanPassIsTrue()
    {
        // arrange
        $databaseMock = Mockery::mock(Database::class);
        $aiMock = Mockery::mock(Ai::class);
        $board = new Board();
        $hands = [
            0 => new Hand([]),
            1 => new Hand([]),
        ];
        $currentPlayer = 0;
        $game = new Game($databaseMock, $aiMock, -1, $board, $hands, $currentPlayer);

        // act
        $canPass = $game->canPass();

        // assert
        $this->assertTrue($canPass);
    }

    #[Test]
    public function givenValidPlayThenCanPassIsFalse()
    {
        // arrange
        $databaseMock = Mockery::mock(Database::class);
        $aiMock = Mockery::mock(Ai::class);
        $board = new Board();
        $hands = [
            0 => new Hand(),
            1 => new Hand(),
        ];
        $currentPlayer = 0;
        $game = new Game($databaseMock, $aiMock, -1, $board, $hands, $currentPlayer);

        // act
        $canPass = $game->canPass();

        // assert
        $this->assertFalse($canPass);
    }

    #[Test]
    public function givenValidMoveThenCanPassIsFalse()
    {
        // arrange
        $databaseMock = Mockery::mock(Database::class);
        $aiMock = Mockery::mock(Ai::class);
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
        $game = new Game($databaseMock, $aiMock, -1, $board, $hands, $currentPlayer);

        // act
        $canPass = $game->canPass();

        // assert
        $this->assertFalse($canPass);
    }

    #[Test]
    public function givenOngoingGameThenWinnerIsNull()
    {
        // arrange
        $databaseMock = Mockery::mock(Database::class);
        $aiMock = Mockery::mock(Ai::class);
        $board = new Board();
        $hands = [
            0 => new Hand(),
            1 => new Hand(),
        ];
        $currentPlayer = 0;
        $game = new Game($databaseMock, $aiMock, -1, $board, $hands, $currentPlayer);

        // act
        $winner = $game->getWinner();

        // assert
        $this->assertNull($winner);
    }

    #[Test]
    public function givenBlackQueenSurroundedThenWinnerIsWhite()
    {
        // arrange
        $databaseMock = Mockery::mock(Database::class);
        $aiMock = Mockery::mock(Ai::class);
        $board = new Board([
            '0,0' => [[1, 'Q']],
            '0,-1' => [[0, 'B']],
            '1,-1' => [[1, 'A']],
            '1,0' => [[0, 'S']],
            '0,1' => [[1, 'G']],
            '-1,1' => [[0, 'A']],
            '-1,0' => [[1, 'G']],
        ]);
        $hands = [
            0 => new Hand(),
            1 => new Hand(),
        ];
        $currentPlayer = 0;
        $game = new Game($databaseMock, $aiMock, -1, $board, $hands, $currentPlayer);

        // act
        $winner = $game->getWinner();

        // assert
        $this->assertSame(0, $winner);
    }

    #[Test]
    public function givenWhiteQueenSurroundedThenWinnerIsBlack()
    {
        // arrange
        $databaseMock = Mockery::mock(Database::class);
        $aiMock = Mockery::mock(Ai::class);
        $board = new Board([
            '0,0' => [[0, 'Q']],
            '0,-1' => [[1, 'B']],
            '1,-1' => [[0, 'A']],
            '1,0' => [[1, 'S']],
            '0,1' => [[0, 'G']],
            '-1,1' => [[1, 'A']],
            '-1,0' => [[0, 'G']],
        ]);
        $hands = [
            0 => new Hand(),
            1 => new Hand(),
        ];
        $currentPlayer = 1;
        $game = new Game($databaseMock, $aiMock, -1, $board, $hands, $currentPlayer);

        // act
        $winner = $game->getWinner();

        // assert
        $this->assertSame(1, $winner);
    }

    #[Test]
    public function givenBlackQueenSurroundedUnderneathBeetleThenWinnerIsWhite()
    {
        // arrange
        $databaseMock = Mockery::mock(Database::class);
        $aiMock = Mockery::mock(Ai::class);
        $board = new Board([
            '0,0' => [[1, 'Q'], [0, 'B']],
            '0,-1' => [[0, 'S']],
            '1,-1' => [[1, 'A']],
            '1,0' => [[0, 'S']],
            '0,1' => [[1, 'G']],
            '-1,1' => [[0, 'A']],
            '-1,0' => [[1, 'G']],
        ]);
        $hands = [
            0 => new Hand(),
            1 => new Hand(),
        ];
        $currentPlayer = 0;
        $game = new Game($databaseMock, $aiMock, -1, $board, $hands, $currentPlayer);

        // act
        $winner = $game->getWinner(true);

        // assert
        $this->assertSame(0, $winner);
    }

    #[Test]
    public function givenBothQueensSurroundedThenWinnerIsTie()
    {
        // arrange
        $databaseMock = Mockery::mock(Database::class);
        $aiMock = Mockery::mock(Ai::class);
        $board = new Board([
            '0,0' => [[0, 'Q']],
            '1,0' => [[1, 'Q']],
            '0,-1' => [[0, 'A']],
            '1,-1' => [[1, 'A']],
            '2,-1' => [[0, 'S']],
            '2,0' => [[1, 'S']],
            '1,1' => [[0, 'G']],
            '0,1' => [[1, 'G']],
            '-1,1' => [[0, 'B']],
            '-1,0' => [[1, 'B']],
        ]);
        $hands = [
            0 => new Hand(),
            1 => new Hand(),
        ];
        $currentPlayer = 1;
        $game = new Game($databaseMock, $aiMock, -1, $board, $hands, $currentPlayer);

        // act
        $winner = $game->getWinner();

        // assert
        $this->assertSame(-1, $winner);
    }

    #[Test]
    public function whenPiecePlayedThenMoveNumberShouldIncreaseByOne()
    {
        // arrange
        $databaseMock = Mockery::mock(Database::class);
        $databaseMock->allows('createMove')->andReturn(1);
        $aiMock = Mockery::mock(Ai::class);
        $game = new Game(
            $databaseMock,
            $aiMock,
            -1,
            new Board(),
            [
                0 => new Hand(),
                1 => new Hand(),
            ],
            0,
            0
        );

        // act
        $game->play('Q', '0,0');
        $moveNumber = $game->getMoveNumber();

        // assert
        $this->assertSame(1, $moveNumber);
    }

    #[Test]
    public function whenPieceMovedThenMoveNumberShouldIncreaseByOne()
    {
        // arrange
        $databaseMock = Mockery::mock(Database::class);
        $databaseMock->allows('createMove')->andReturn(1);
        $aiMock = Mockery::mock(Ai::class);
        $game = new Game(
            $databaseMock,
            $aiMock,
            -1,
            new Board([
                '0,0' => [[0, 'Q']],
                '1,0' => [[1, 'Q']],
            ]),
            [
                0 => new Hand([
                    'Q' => 0,
                ]),
                1 => new Hand([
                    'Q' => 0,
                ]),
            ],
            0,
            0
        );

        // act
        $game->move('0,0', '0,1');
        $moveNumber = $game->getMoveNumber();

        // assert
        $this->assertSame(1, $moveNumber);
    }
}
