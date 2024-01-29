<?php

namespace App\Entity;

use App\Exception\InvalidMoveException;

class Game
{
    private int $id;
    private Board $board;
    /** @var Hand[] $hands */
    private array $hands;
    private int $currentPlayer;

    public function __construct(Board $board = null, array $hands = null, int $currentPlayer = 0)
    {
        $this->id = Database::getInstance()->createGame();
        $this->board = $board ?? new Board();
        $this->hands = $hands ?? [0 => new Hand(), 1 => new Hand()];
        $this->currentPlayer = $currentPlayer;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBoard(): Board
    {
        return $this->board;
    }

    /**
     * @return Hand[]
     */
    public function getHands(): array
    {
        return $this->hands;
    }

    public function getCurrentPlayer(): int
    {
        return $this->currentPlayer;
    }

    public function getState(): string
    {
        return serialize([
            [0 => $this->hands[0]->getPieces(), 1 => $this->hands[1]->getPieces()],
            $this->board->getTiles(),
            $this->currentPlayer,
        ]);
    }

    public function setState(string $state): void
    {
        list($a, $b, $c) = unserialize($state);
        $this->hands = [
            0 => new Hand($a[0]),
            1 => new Hand($a[1]),
        ];
        $this->board = new Board($b);
        $this->currentPlayer = $c;
    }

    private function switchCurrentPlayer(): void
    {
        $this->currentPlayer = 1 - $this->currentPlayer;
    }

    /**
     * @throws InvalidMoveException
     */
    public function play(string $piece, string $to): void
    {
        $hand = $this->hands[$this->currentPlayer];

        if (!$hand->hasPiece($piece)) {
            throw new InvalidMoveException('Player does not have tile');
        } elseif (!$this->board->isPositionEmpty($to)) {
            throw new InvalidMoveException('Board position is not empty');
        } elseif (count($this->board->getTiles()) && !$this->board->hasNeighbour($to)) {
            throw new InvalidMoveException('board position has no neighbour');
        } elseif (
            $this->hands[$this->currentPlayer]->getTotalSum() < 11 &&
            !$this->board->neighboursAreSameColor($this->currentPlayer, $to)
        ) {
            throw new InvalidMoveException('Board position has opposing neighbour');
        } elseif ($this->hands[$this->currentPlayer]->getTotalSum() <= 8 && $hand->hasPiece('Q')) {
            throw new InvalidMoveException('Must play queen bee');
        } else {
            $this->board->setPosition($to, $this->currentPlayer, $piece);
            $hand->removePiece($piece);
            $this->switchCurrentPlayer();
            $_SESSION['last_move'] = Database::getInstance()->createMove(
                $this,
                "play",
                $piece,
                $to,
                $_SESSION['last_move']
            );
        }
    }

    /**
     * @throws InvalidMoveException
     */
    public function move(string $from, string $to): void
    {
        $hand = $this->hands[$this->currentPlayer];

        if ($this->board->isPositionEmpty($from)) {
            throw new InvalidMoveException('Board position is empty');
        } elseif (!$this->board->isTileOwnedByPlayer($from, $this->currentPlayer)) {
            throw new InvalidMoveException('Tile is not owned by player');
        } elseif ($hand->hasPiece('Q')) {
            throw new InvalidMoveException('Queen bee is not played');
        } else {
            $tile = $this->board->popTile($from);
            if (!$this->board->hasNeighbour($to)) {
                throw new InvalidMoveException('Move would split hive');
            } else {
                $all = $this->board->getAllPositions();
                $queue = [array_shift($all)];
                while ($queue) {
                    $next = explode(',', array_shift($queue));
                    foreach (Board::OFFSETS as $pq) {
                        list($p, $q) = $pq;
                        $p += $next[0];
                        $q += $next[1];
                        if (in_array("$p,$q", $all)) {
                            $queue[] = "$p,$q";
                            $all = array_diff($all, ["$p,$q"]);
                        }
                    }
                }
                if ($all) {
                    throw new InvalidMoveException('Move would split hive');
                } else {
                    if ($from == $to) {
                        throw new InvalidMoveException('Tile must move');
                    } elseif (!$this->board->isPositionEmpty($to) && $tile[1] != "B") {
                        throw new InvalidMoveException('Tile not empty');
                    } elseif ($tile[1] == "Q" || $tile[1] == "B") {
                        if (!$this->board->slide($from, $to)) {
                            throw new InvalidMoveException('Tile must slide');
                        }
                    }
                }
            }
            if (isset($_SESSION['error'])) {
                $this->board->pushTile($from, $tile);
            } else {
                $this->board->pushTile($to, $tile);
                $this->switchCurrentPlayer();
                $_SESSION['last_move'] = Database::getInstance()->createMove(
                    $this,
                    "move",
                    $from, $to,
                    $_SESSION['last_move']
                );
            }
        }
    }

    public function pass(): void
    {
        $_SESSION['last_move'] = Database::getInstance()->createPassMove(
            $this,
            $_SESSION['last_move']
        );
        $this->switchCurrentPlayer();
    }

    public function undo(): void
    {
        $result = Database::getInstance()->findMoveById($_SESSION['last_move']);
        $_SESSION['last_move'] = $result[5];
        $this->setState($result[6]);
    }
}
