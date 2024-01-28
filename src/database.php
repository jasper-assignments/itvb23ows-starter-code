<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use App\Board;

function getState() {
    /** @var Board $board */
    $board = $_SESSION['board'];

    return serialize([$_SESSION['hand'], $board->getTiles(), $_SESSION['player']]);
}

function setState(string $state) {
    list($a, $b, $c) = unserialize($state);
    $_SESSION['hand'] = $a;
    $_SESSION['board'] = new Board($b);
    $_SESSION['player'] = $c;
}

return new mysqli($_ENV['PHP_MYSQL_HOSTNAME'], 'root', $_ENV['MYSQL_ROOT_PASSWORD'], $_ENV['MYSQL_DATABASE']);
