<?php

use App\Entity\Board;
use App\Entity\Database;
use App\Entity\Game;
use App\Entity\Hand;
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
                        "Q" => 0,
                        "B" => 2,
                        "S" => 2,
                        "A" => 3,
                        "G" => 3,
                    ]),
                    1 => new Hand([
                        "Q" => 0,
                        "B" => 2,
                        "S" => 2,
                        "A" => 3,
                        "G" => 3,
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
                        "Q" => 0,
                        "B" => 1,
                        "S" => 1,
                        "A" => 3,
                        "G" => 3,
                    ]),
                    1 => new Hand([
                        "Q" => 0,
                        "B" => 1,
                        "S" => 2,
                        "A" => 3,
                        "G" => 3,
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
        $game = new Game($databaseMock, -1, $board, $hands, $currentPlayer);

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
        $game = new Game($databaseMock, -1, $board, $hands, $currentPlayer);

        // act
        $playPositions = $game->getValidPlayPositions();

        // assert
        $this->assertEmpty(array_diff($playPositions, $validPlayPositions));
    }
}
