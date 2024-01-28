<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Board;
use App\Hand;
use App\Database;

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

header('Location: index.php');
