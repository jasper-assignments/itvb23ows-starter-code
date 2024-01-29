<?php

namespace App\Entity;

use mysqli;

class Database
{
    private static ?Database $instance;

    public static function getInstance(): Database
    {
        if (!isset(self::$instance)) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

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
            WHERE id = ' . $id
        );
        $stmt->execute();
        return $stmt->get_result()->fetch_array();
    }

    public function findMovesByGameId(int $gameId): array
    {
        $stmt = $this->connection->prepare('
            SELECT * FROM moves
            WHERE game_id = ' . $gameId
        );
        $stmt->execute();
        return $stmt->get_result()->fetch_all();
    }

    public function createMove(Game $game, string $type, string $moveFrom, string $moveTo, ?int $lastMoveId): int
    {
        $stmt = $this->connection->prepare('
            INSERT INTO moves (game_id, type, move_from, move_to, previous_id, state)
            VALUES (?, ?, ?, ?, ?, ?)
        ');

        $state = $game->getState();
        $gameId = $game->getId();

        $stmt->bind_param('isssis', $gameId, $type, $moveFrom, $moveTo, $lastMoveId, $state);
        $stmt->execute();

        return $stmt->insert_id;
    }

    public function createPassMove(Game $game, ?int $lastMoveId): int
    {
        $stmt = $this->connection->prepare('
            INSERT INTO moves (game_id, type, move_from, move_to, previous_id, state)
            VALUES (?, "pass", null, null, ?, ?)
        ');

        $state = $game->getState();
        $gameId = $game->getId();

        $stmt->bind_param('iis', $gameId, $lastMoveId, $state);
        $stmt->execute();

        return $stmt->insert_id;
    }
}
