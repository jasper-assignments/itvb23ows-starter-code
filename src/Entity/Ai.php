<?php

namespace App\Entity;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Ai
{
    private Client $client;
    
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param int $moveNumber
     * @param array{Hand, Hand} $hands
     * @param Board $board
     * @return array
     */
    public function buildBody(int $moveNumber, array $hands, Board $board): array
    {
        return [
            'move_number' => $moveNumber,
            'hand' => [
                $hands[0]->getPieces(),
                $hands[1]->getPieces(),
            ],
            'board' => $board->getTiles(),
        ];
    }

    /**
     * @param int $moveNumber
     * @param array{Hand, Hand} $hands
     * @param Board $board
     * @return array{string, ?string, ?string}
     * @throws GuzzleException
     */
    public function getSuggestion(int $moveNumber, array $hands, Board $board)
    {
        $response = $this->client->post('', [
            'json' => $this->buildBody($moveNumber, $hands, $board),
        ]);
        return json_decode($response->getBody()->getContents());
    }
}
