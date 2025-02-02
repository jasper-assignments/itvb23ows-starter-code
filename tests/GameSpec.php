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
        $databaseMock->allows('createMove')->andReturns(1);
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
        $databaseMock->allows('createMove')->andReturns(1);
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

    #[Test]
    public function whenMakingAiMoveThenGetSuggestionShouldGetCalledWithRightParameters()
    {
        // arrange
        $databaseMock = Mockery::mock(Database::class);
        $aiSpy = Mockery::spy(Ai::class);
        $aiSpy->allows('getSuggestion');
        $board = new Board([
            '0,0' => [[1, 'Q']],
            '0,1' => [[0, 'Q']],
            '0,-1' => [[0, 'A']],
        ]);
        $hands = [
            0 => new Hand([
                'Q' => 0,
                'B' => 2,
                'S' => 2,
                'A' => 3,
                'G' => 3,
            ]),
            1 => new Hand([
                'Q' => 0,
                'B' => 2,
                'S' => 2,
                'A' => 2,
                'G' => 3,
            ]),
        ];
        $currentPlayer = 1;
        $moveNumber = 3;
        $game = new Game(
            $databaseMock,
            $aiSpy,
            -1,
            $board,
            $hands,
            $currentPlayer,
            $moveNumber
        );

        // act
        $game->makeAiMove();

        // assert
        $aiSpy->shouldHaveReceived('getSuggestion', [$moveNumber, $hands, $board]);
    }

    #[Test]
    public function whenMakingAiMoveWithPlayAsSuggestionThenCreateMoveShouldGetCalledWithSuggestion()
    {
        // arrange
        $databaseMock = Mockery::mock(Database::class);
        $databaseMock->allows('createMove')->andReturns(1);
        $aiMock = Mockery::mock(Ai::class);
        $aiMock->allows('getSuggestion')->andReturns(['play', 'Q', '0,0']);
        $board = new Board();
        $hands = [
            0 => new Hand(),
            1 => new Hand(),
        ];
        $currentPlayer = 0;
        $moveNumber = 0;
        $game = new Game($databaseMock, $aiMock, -1, $board, $hands, $currentPlayer, $moveNumber);

        // act
        $game->makeAiMove();

        // assert
        $databaseMock->shouldHaveReceived('createMove', [-1, 'play', 'Q', '0,0', Mockery::any(), Mockery::any()]);
    }

    #[Test]
    public function whenMakingAiMoveWithMoveAsSuggestionThenCreateMoveShouldGetCalledWithSuggestion()
    {
        // arrange
        $databaseMock = Mockery::mock(Database::class);
        $databaseMock->allows('createMove')->andReturns(1);
        $aiMock = Mockery::mock(Ai::class);
        $aiMock->allows('getSuggestion')->andReturns(['move', '0,0', '0,1']);
        $board = new Board([
            '0,0' => [[0, 'Q']],
            '1,0' => [[1, 'Q']],
        ]);
        $hands = [
            0 => new Hand([
                'Q' => 0,
                'B' => 2,
                'S' => 2,
                'A' => 3,
                'G' => 3,
            ]),
            1 => new Hand([
                'Q' => 0,
                'B' => 2,
                'S' => 2,
                'A' => 3,
                'G' => 3,
            ]),
        ];
        $currentPlayer = 0;
        $moveNumber = 2;
        $game = new Game($databaseMock, $aiMock, -1, $board, $hands, $currentPlayer, $moveNumber);

        // act
        $game->makeAiMove();

        // assert
        $databaseMock->shouldHaveReceived('createMove', [-1, 'move', '0,0', '0,1', Mockery::any(), Mockery::any()]);
    }

    #[Test]
    public function whenMakingAiMoveWithPassAsSuggestionThenCreatePassMoveShouldGetCalled()
    {
        // arrange
        $databaseMock = Mockery::mock(Database::class);
        $databaseMock->allows('createPassMove')->andReturns(1);
        $aiMock = Mockery::mock(Ai::class);
        $aiMock->allows('getSuggestion')->andReturns(['pass', null, null]);
        $board = new Board([
            '0,0' => [[0, 'Q']],
            '1,0' => [[1, 'Q']],
        ]);
        $hands = [
            0 => new Hand(),
            1 => new Hand(),
        ];
        $currentPlayer = 0;
        $moveNumber = 2;
        $game = new Game($databaseMock, $aiMock, -1, $board, $hands, $currentPlayer, $moveNumber);

        // act
        $game->makeAiMove();

        // assert
        $databaseMock->shouldHaveReceived('createPassMove', [-1, Mockery::any(), Mockery::any()]);
    }
}
