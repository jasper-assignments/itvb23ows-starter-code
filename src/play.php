<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use App\Board;

session_start();

/** @var string $piece */
$piece = $_POST['piece'];
/** @var string $to */
$to = $_POST['to'];

/** @var int $player */
$player = $_SESSION['player'];
/** @var Board $board */
$board = $_SESSION['board'];
$hand = $_SESSION['hand'][$player];

if (!$hand[$piece]) {
    $_SESSION['error'] = "Player does not have tile";
} elseif (!$board->isPositionEmpty($to)) {
    $_SESSION['error'] = 'Board position is not empty';
} elseif (count($board->getTiles()) && !$board->hasNeighbour($to)) {
    $_SESSION['error'] = "board position has no neighbour";
} elseif (array_sum($hand) < 11 && !$board->neighboursAreSameColor($player, $to)) {
    $_SESSION['error'] = "Board position has opposing neighbour";
} elseif (array_sum($hand) <= 8 && $hand['Q']) {
    $_SESSION['error'] = 'Must play queen bee';
} else {
    $board->setPosition($to, $_SESSION['player'], $piece);
    $_SESSION['hand'][$player][$piece]--;
    $_SESSION['player'] = 1 - $_SESSION['player'];
    $db = include_once 'database.php';
    $stmt = $db->prepare('
        INSERT INTO moves (game_id, type, move_from, move_to, previous_id, state)
        VALUES (?, "play", ?, ?, ?, ?)
    ');
    $state = getState();
    $stmt->bind_param('issis', $_SESSION['game_id'], $piece, $to, $_SESSION['last_move'], $state);
    $stmt->execute();
    $_SESSION['last_move'] = $db->insert_id;
}

header('Location: index.php');
