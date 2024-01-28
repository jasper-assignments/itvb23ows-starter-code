<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use App\Board;
use App\Hand;

session_start();

$_SESSION['board'] = new Board();
$_SESSION['hand'] = [
    0 => new Hand(),
    1 => new Hand(),
];
$_SESSION['player'] = 0;

$db = include_once 'database.php';
$db->prepare('
    INSERT INTO games
    VALUES ()
')->execute();
$_SESSION['game_id'] = $db->insert_id;

header('Location: index.php');
