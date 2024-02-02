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

    public function buildBody(int $moveNumber): array
    {
        return [
            'move_number' => $moveNumber,
        ];
    }
}
