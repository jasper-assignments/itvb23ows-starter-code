<?php

use App\Entity\Ai;
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

        // act
        $body = $ai->buildBody($moveNumber);

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

        // act
        $body = $ai->buildBody($moveNumber, $hands);

        // assert
        $this->assertSame($hands[0]->getPieces(), $body['hand'][0]);
    }
}
