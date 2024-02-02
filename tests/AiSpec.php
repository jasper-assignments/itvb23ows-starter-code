<?php

use App\Entity\Ai;
use App\Entity\Board;
use App\Entity\Hand;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class AiSpec extends TestCase
{
    use MockeryPHPUnitIntegration;

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

    #[Test]
    public function givenDataThenEnsurePostGetsCalledWithRightParameters()
    {
        // arrange
        $guzzleClientSpy = Mockery::spy(Client::class);
        $guzzleClientSpy->allows('post')->andReturn(new Response());
        $ai = new Ai($guzzleClientSpy);
        $moveNumber = 1;
        $hands = [
            0 => new Hand(),
            1 => new Hand(),
        ];
        $board = new Board();

        // act
        $ai->getSuggestion($moveNumber, $hands, $board);

        // assert
        $guzzleClientSpy->shouldHaveReceived()->post('', [
            'json' => [
                'move_number' => $moveNumber,
                'hand' => [
                    $hands[0]->getPieces(),
                    $hands[1]->getPieces(),
                ],
                'board' => $board->getTiles(),
            ]
        ]);
    }

    #[Test]
    public function givenDataThenEnsurePostResponseGetsDecodedCorrectly()
    {
        // arrange
        $guzzleClientMock = Mockery::mock(Client::class);
        $guzzleClientMock->allows('post')->andReturn(new Response(body: '["play", "B", "0,0"]'));
        $ai = new Ai($guzzleClientMock);
        $moveNumber = 1;
        $hands = [
            0 => new Hand(),
            1 => new Hand(),
        ];
        $board = new Board();

        // act
        $suggestion = $ai->getSuggestion($moveNumber, $hands, $board);

        // assert
        $this->assertSame(['play', 'B', '0,0'], $suggestion);
    }
}
