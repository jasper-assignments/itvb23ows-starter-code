<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use App\Board;
use App\Hand;

function getState() {
    /** @var Board $board */
    $board = $_SESSION['board'];
    /** @var Hand[] $hands */
    $hands = $_SESSION['hand'];

    return serialize([
        [
            0 => $hands[0]->getPieces(),
            1 => $hands[1]->getPieces(),
        ],
        $board->getTiles(),
        $_SESSION['player']
    ]);
}

function setState(string $state) {
    list($a, $b, $c) = unserialize($state);
    $_SESSION['hand'] = [
        0 => new Hand($a[0]),
        1 => new Hand($a[1]),
    ];
    $_SESSION['board'] = new Board($b);
    $_SESSION['player'] = $c;
}

return new mysqli($_ENV['PHP_MYSQL_HOSTNAME'], 'root', $_ENV['MYSQL_ROOT_PASSWORD'], $_ENV['MYSQL_DATABASE']);
