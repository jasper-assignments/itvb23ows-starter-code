<?php

namespace App\Controller;

use App\Board;
use App\Database;
use App\Hand;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    public function index(): Response
    {
        return render_template("index");
    }

    public function play(): Response
    {
        session_start();

        /** @var string $piece */
        $piece = $_POST['piece'];
        /** @var string $to */
        $to = $_POST['to'];

        /** @var int $player */
        $player = $_SESSION['player'];
        /** @var Board $board */
        $board = $_SESSION['board'];
        /** @var Hand $hand */
        $hand = $_SESSION['hand'][$player];

        if (!$hand->hasPiece($piece)) {
            $_SESSION['error'] = "Player does not have tile";
        } elseif (!$board->isPositionEmpty($to)) {
            $_SESSION['error'] = 'Board position is not empty';
        } elseif (count($board->getTiles()) && !$board->hasNeighbour($to)) {
            $_SESSION['error'] = "board position has no neighbour";
        } elseif ($hand->getTotalSum() < 11 && !$board->neighboursAreSameColor($player, $to)) {
            $_SESSION['error'] = "Board position has opposing neighbour";
        } elseif ($hand->getTotalSum() <= 8 && $hand->hasPiece('Q')) {
            $_SESSION['error'] = 'Must play queen bee';
        } else {
            $board->setPosition($to, $_SESSION['player'], $piece);
            $hand->removePiece($piece);
            $_SESSION['player'] = 1 - $_SESSION['player'];
            $_SESSION['last_move'] = Database::getInstance()->createMove(
                $_SESSION['game_id'],
                "play",
                $piece,
                $to,
                $_SESSION['last_move']
            );
        }

        return new RedirectResponse("/");
    }

    public function move(): Response
    {
        session_start();

        /** @var string $from */
        $from = $_POST['from'];
        /** @var string $to */
        $to = $_POST['to'];

        /** @var int $player */
        $player = $_SESSION['player'];
        /** @var Board $board */
        $board = $_SESSION['board'];
        /** @var Hand $hand */
        $hand = $_SESSION['hand'][$player];
        unset($_SESSION['error']);

        if ($board->isPositionEmpty($from)) {
            $_SESSION['error'] = 'Board position is empty';
        } elseif (!$board->isTileOwnedByPlayer($from, $player)) {
            $_SESSION['error'] = "Tile is not owned by player";
        } elseif ($hand->hasPiece('Q')) {
            $_SESSION['error'] = "Queen bee is not played";
        } else {
            $tile = $board->popTile($from);
            if (!$board->hasNeighbour($to)) {
                $_SESSION['error'] = "Move would split hive";
            } else {
                $all = $board->getAllPositions();
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
                    $_SESSION['error'] = "Move would split hive";
                } else {
                    if ($from == $to) {
                        $_SESSION['error'] = 'Tile must move';
                    } elseif (!$board->isPositionEmpty($to) && $tile[1] != "B") {
                        $_SESSION['error'] = 'Tile not empty';
                    } elseif ($tile[1] == "Q" || $tile[1] == "B") {
                        if (!$board->slide($from, $to)) {
                            $_SESSION['error'] = 'Tile must slide';
                        }
                    }
                }
            }
            if (isset($_SESSION['error'])) {
                $board->pushTile($from, $tile);
            } else {
                $board->pushTile($to, $tile);
                $_SESSION['player'] = 1 - $_SESSION['player'];
                $_SESSION['last_move'] = Database::getInstance()->createMove(
                    $_SESSION['game_id'],
                    "move",
                    $from, $to,
                    $_SESSION['last_move']
                );
            }
            $_SESSION['board'] = $board;
        }

        return new RedirectResponse("/");
    }

    public function pass(): Response
    {
        session_start();

        $_SESSION['last_move'] = Database::getInstance()->createPassMove(
            $_SESSION['game_id'],
            $_SESSION['last_move']
        );
        $_SESSION['player'] = 1 - $_SESSION['player'];

        return new RedirectResponse("/");
    }

    public function restart(): Response
    {
        session_start();

        $_SESSION['board'] = new Board();
        $_SESSION['hand'] = [
            0 => new Hand(),
            1 => new Hand(),
        ];
        $_SESSION['player'] = 0;

        $_SESSION['game_id'] = Database::getInstance()->createGame();

        return new RedirectResponse("/");
    }

    public function undo(): Response
    {
        session_start();

        $result = Database::getInstance()->findMoveById($_SESSION['last_move']);
        $_SESSION['last_move'] = $result[5];
        Database::setState($result[6]);
        return new RedirectResponse("/");
    }
}
