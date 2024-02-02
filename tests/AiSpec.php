<?php

use App\Entity\Ai;
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
}