<?php

namespace App\Controller;

use App\Entity\Database;
use App\Entity\Game;
use App\Exception\InvalidMoveException;
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

        /** @var Game $game */
        $game = $_SESSION['game'];
        try {
            $game->play($piece, $to);
        } catch (InvalidMoveException $exception) {
            $_SESSION['error'] = $exception->getMessage();
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

        unset($_SESSION['error']);

        /** @var Game $game */
        $game = $_SESSION['game'];
        try {
            $game->move($from, $to);
        } catch (InvalidMoveException $exception) {
            $_SESSION['error'] = $exception->getMessage();
        }

        return new RedirectResponse("/");
    }

    public function pass(): Response
    {
        session_start();

        /** @var Game $game */
        $game = $_SESSION['game'];
        $game->pass();

        return new RedirectResponse("/");
    }

    public function restart(): Response
    {
        session_start();
        $_SESSION['game'] = new Game();
        return new RedirectResponse("/");
    }

    public function undo(): Response
    {
        session_start();

        $result = Database::getInstance()->findMoveById($_SESSION['last_move']);
        $_SESSION['last_move'] = $result[5];

        /** @var Game $game */
        $game = $_SESSION['game'];
        $game->setState($result[6]);

        return new RedirectResponse("/");
    }
}
