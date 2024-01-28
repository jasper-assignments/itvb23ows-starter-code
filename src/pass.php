<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Database;

session_start();

$_SESSION['last_move'] = Database::getInstance()->createPassMove(
    $_SESSION['game_id'],
    $_SESSION['last_move']
);
$_SESSION['player'] = 1 - $_SESSION['player'];

header('Location: index.php');
