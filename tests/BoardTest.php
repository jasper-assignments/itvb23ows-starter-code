<?php

use App\Entity\Board;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class BoardTest extends TestCase
{
    public static function dataProvider(): array
    {
        return [
            'only 2 queens' => [
                'tiles' => [
                    '0,0' => [[0, 'Q']],
                    '0,1' => [[1, 'Q']],
                ],
                'currentPlayer' => 0,
            ],
            '3 moves for white, 2 moves for black' => [
                'tiles' => [
                    '0,0' => [[0, 'Q']],
                    '0,1' => [[1, 'Q']],
                    '0,-1' => [[0, 'B']],
                    '-1,2' => [[1, 'B']],
                    '1,-1' => [[0, 'S']],
                ],
                'currentPlayer' => 1,
            ]
        ];
    }

    #[DataProvider('dataProvider')]
    public function testPositionsOwnedByPlayerOnlyContainsPositionsOwnedByPlayerWithProvider(
        array $tiles,
        int $currentPlayer
    )
    {
        // arrange
        $board = new Board($tiles);

        // act
        $ownedPositions = $board->getAllPositionsOwnedByPlayer($currentPlayer);

        // assert
        foreach ($ownedPositions as $pos) {
            $this->assertTrue($board->isTileOwnedByPlayer($pos, $currentPlayer));
        }
    }
}
