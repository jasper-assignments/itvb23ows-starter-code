<?php

use App\Entity\Ai;
use GuzzleHttp\Client;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class AiSpec extends TestCase
{
    #[Test]
    public function givenIntMoveNumberThenBuildBodyAddsCorrectlyToArray()
    {
        // arrange
        $guzzleClientMock = Mockery::mock(Client::class);
        $ai = new Ai($guzzleClientMock);

        // act
        $body = $ai->buildBody(1);
        $moveNumber = $body['move_number'];

        // assert
        $this->assertSame(1, $moveNumber);
    }
}