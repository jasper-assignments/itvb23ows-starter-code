<?php

use App\Entity\Ai;
use App\Entity\Board;
use App\Entity\Database;
use App\Entity\Game;
use App\Entity\Hand;
use App\Exception\InvalidMoveException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    public static function dataProvider(): array
    {
        return [
            'new game' => [
                'board' => new Board(),
                'hands' => [
                    0 => new Hand(),
                    1 => new Hand(),
                ],
                'currentPlayer' => 0,
                'validPlayPositions' => ['0,0'],
            ],
            'only 2 queens' => [
                'board' => new Board([
                    '0,0' => [[0, 'Q']],
                    '0,1' => [[1, 'Q']],
                ]),
                'hands' => [
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
                ],
                'currentPlayer' => 0,
                'validPlayPositions' => ['0,-1', '-1,0', '1,-1'],
            ],
            '3 moves for white, 2 moves for black' => [
                'board' => new Board([
                    '0,0' => [[0, 'Q']],
                    '0,1' => [[1, 'Q']],
                    '0,-1' => [[0, 'B']],
                    '-1,2' => [[1, 'B']],
                    '1,-1' => [[0, 'S']],
                ]),
                'hands' => [
                    0 => new Hand([
                        'Q' => 0,
                        'B' => 1,
                        'S' => 1,
                        'A' => 3,
                        'G' => 3,
                    ]),
                    1 => new Hand([
                        'Q' => 0,
                        'B' => 1,
                        'S' => 2,
                        'A' => 3,
                        'G' => 3,
                    ]),
                ],
                'currentPlayer' => 1,
                'validPlayPositions' => ['0,2', '-1,3', '1,1', '-2,3'],
            ]
        ];
    }

    #[DataProvider('dataProvider')]
    public function testValidPlayPositionsOnlyContainsValidPlaysWithProvider(
        Board $board,
        array $hands,
        int $currentPlayer,
        array $validPlayPositions
    )
    {
        // arrange
        $databaseMock = Mockery::mock(Database::class);
        $aiMock = Mockery::mock(Ai::class);
        $game = new Game($databaseMock, $aiMock, -1, $board, $hands, $currentPlayer);

        // act
        $playPositions = $game->getValidPlayPositions();

        // assert
        $this->assertEmpty(array_diff($validPlayPositions, $playPositions));
    }

    #[DataProvider('dataProvider')]
    public function testValidPlayPositionsContainsAllValidPlaysWithProvider(
        Board $board,
        array $hands,
        int $currentPlayer,
        array $validPlayPositions
    )
    {
        // arrange
        $databaseMock = Mockery::mock(Database::class);
        $aiMock = Mockery::mock(Ai::class);
        $game = new Game($databaseMock, $aiMock, -1, $board, $hands, $currentPlayer);

        // act
        $playPositions = $game->getValidPlayPositions();

        // assert
        $this->assertEmpty(array_diff($playPositions, $validPlayPositions));
    }

    public function testPlayOnPositionThatWasMoved()
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
                '0,0' => [[1, 'Q']],
                '0,1' => [[0, 'Q']],
                '0,-1' => [[0, 'A']],
            ]),
            [
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
            ],
            0
        );

        // act
        $game->move('0,-1', '0,2');
        [$valid, $err] = $game->isPlayValid('0,-1');

        // assert
        $this->assertTrue($valid);
    }

    public function testQueenMustBePlayedOnFourthTurn()
    {
        // arrange
        $databaseMock = Mockery::mock(Database::class);
        $aiMock = Mockery::mock(Ai::class);
        $board = new Board([
            '1,-1' => [[0, 'S']],
            '-1,0' => [[0, 'B']],
            '0,0' => [[0, 'B']],
            '0,1' => [[1, 'B']],
            '1,1' => [[1, 'B']],
            '-1,2' => [[1, 'A']],
        ]);
        $hands = [
            0 => new Hand([
                'Q' => 1,
                'B' => 0,
                'S' => 1,
                'A' => 3,
                'G' => 3,
            ]),
            1 => new Hand([
                'Q' => 1,
                'B' => 0,
                'S' => 2,
                'A' => 2,
                'G' => 3,
            ]),
        ];
        $game = new Game($databaseMock, $aiMock, -1, $board, $hands, 0);

        // expect
        // This test follows Arrange, Expect, Act rather than the usual Arrange, Act, Assert
        // due to it expecting an exception. More info: https://docs.phpunit.de/en/10.5/fixtures.html
        $this->expectException(InvalidMoveException::class);

        // act
        $game->play('S', '0,-1');
    }

    public function testUndoCannotBeDoneWithNoMoves()
    {
        // arrange
        $databaseMock = Mockery::mock(Database::class);
        $databaseMock->allows('createGame')->andReturns(1);
        $aiMock = Mockery::mock(Ai::class);
        $game = new Game($databaseMock, $aiMock);

        // act
        $canUndo = $game->canUndo();

        // assert
        $this->assertFalse($canUndo);
    }

    public function testUndoGoesBackToPreviousStateAfterPlaying()
    {
        // arrange
        $databaseMock = Mockery::mock(Database::class);
        $aiMock = Mockery::mock(Ai::class);
        $board = new Board([
            '0,0' => [[0, 'Q']],
            '0,1' => [[1, 'Q']],
        ]);
        $hands = [
            0 => new Hand(),
            1 => new Hand(),
        ];
        $currentPlayer = 0;
        $moveNumber = 2;
        $lastMoveId = 2;
        $game = new Game($databaseMock, $aiMock, -1, $board, $hands, $currentPlayer, $moveNumber, $lastMoveId);
        $initialGameState = $game->getState();
        $databaseMock->allows('createMove')
            ->with(-1, 'play', 'B', '0,-1', 2, $initialGameState)
            ->andReturns(3);
        $databaseMock->allows('findMoveById')
            ->with(3)
            ->andReturns([5 => 2, 6 => $initialGameState]);

        // act
        $game->play('B', '0,-1');
        $game->undo();
        $gameSate = $game->getState();

        // assert
        $this->assertEquals($initialGameState, $gameSate);
    }
}
