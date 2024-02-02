<?php

namespace App\Entity;

use mysqli;

class Database
{
    private mysqli $connection;

    public function __construct()
    {
        $this->connection = new mysqli(
            $_ENV['PHP_MYSQL_HOSTNAME'],
            'root',
            $_ENV['MYSQL_ROOT_PASSWORD'],
            $_ENV['MYSQL_DATABASE']
        );
    }

    public function createGame(): int
    {
        $stmt = $this->connection->prepare('
            INSERT INTO games
            VALUES ()
        ');

        $stmt->execute();

        return $stmt->insert_id;
    }

    public function findMoveById(int $id): array
    {
        $stmt = $this->connection->prepare('
            SELECT * FROM moves
            WHERE id = ?
        ');

        $stmt->bind_param('i', $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_array();
    }

    public function findMovesByGameId(int $gameId): array
    {
        $stmt = $this->connection->prepare('
            SELECT * FROM moves
            WHERE game_id = ?
        ');

        $stmt->bind_param('i', $gameId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all();
    }

    public function createMove(int $gameId, string $type, string $moveFrom, string $moveTo, ?int $lastMoveId, string $state): int
    {
        $stmt = $this->connection->prepare('
            INSERT INTO moves (game_id, type, move_from, move_to, previous_id, state)
            VALUES (?, ?, ?, ?, ?, ?)
        ');

        $stmt->bind_param('isssis', $gameId, $type, $moveFrom, $moveTo, $lastMoveId, $state);
        $stmt->execute();

        return $stmt->insert_id;
    }

    public function createPassMove(int $gameId, ?int $lastMoveId, string $state): int
    {
        $stmt = $this->connection->prepare('
            INSERT INTO moves (game_id, type, move_from, move_to, previous_id, state)
            VALUES (?, ?, null, null, ?, ?)
        ');

        $type = 'pass';

        $stmt->bind_param('isis', $gameId, $type, $lastMoveId, $state);
        $stmt->execute();

        return $stmt->insert_id;
    }
}
