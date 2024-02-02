<?php

use App\Entity\Ai;
use App\Entity\Board;
use App\Entity\Hand;
use GuzzleHttp\Client;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class AiSpec extends TestCase
{
    #[Test]
    public function givenIntMoveNumberThenBuildBodyAddsCorrectlyToBody()
    {
        // arrange
        $guzzleClientMock = Mockery::mock(Client::class);
        $ai = new Ai($guzzleClientMock);
        $moveNumber = 1;
        $hands = [
            0 => new Hand(),
            1 => new Hand(),
        ];
        $board = new Board();

        // act
        $body = $ai->buildBody($moveNumber, $hands, $board);

        // assert
        $this->assertSame(1, $body['move_number']);
    }

    #[Test]
    public function givenHandsArrayThenBuildBodyAddsPiecesCorrectlyToBody()
    {
        // arrange
        $guzzleClientMock = Mockery::mock(Client::class);
        $ai = new Ai($guzzleClientMock);
        $moveNumber = 1;
        $hands = [
            0 => new Hand(),
            1 => new Hand(),
        ];
        $board = new Board();

        // act
        $body = $ai->buildBody($moveNumber, $hands, $board);

        // assert
        $this->assertSame($hands[0]->getPieces(), $body['hand'][0]);
    }

    #[Test]
    public function givenBoardThenBuildBodyAddsTilesCorrectlyToBody()
    {
        // arrange
        $guzzleClientMock = Mockery::mock(Client::class);
        $ai = new Ai($guzzleClientMock);
        $moveNumber = 1;
        $hands = [
            0 => new Hand(),
            1 => new Hand(),
        ];
        $board = new Board();

        // act
        $body = $ai->buildBody($moveNumber, $hands, $board);

        // assert
        $this->assertSame($board->getTiles(), $body['board']);
    }
}
