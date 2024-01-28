<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use App\Board;
use App\Hand;
use App\Database;

session_start();

$_SESSION['board'] = new Board();
$_SESSION['hand'] = [
    0 => new Hand(),
    1 => new Hand(),
];
$_SESSION['player'] = 0;

$_SESSION['game_id'] = Database::getInstance()->createGame();

header('Location: index.php');
