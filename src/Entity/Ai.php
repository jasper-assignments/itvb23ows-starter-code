<?php

namespace App\Entity;

use GuzzleHttp\Client;

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
}
