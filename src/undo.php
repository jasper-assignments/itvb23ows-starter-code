<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use App\Database;

session_start();

$result = Database::getInstance()->findMoveById($_SESSION['last_move']);
$_SESSION['last_move'] = $result[5];
Database::setState($result[6]);
header('Location: index.php');
