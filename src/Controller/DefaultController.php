<?php

namespace App\Controller;

use App\Entity\Database;
use App\Entity\Game;
use App\Exception\InvalidMoveException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function index(): Response
    {
        return render_template('index');
    }

    public function play(): Response
    {
        session_start();

        /** @var string $piece */
        $piece = $_POST['piece'];
        /** @var string $to */
        $to = $_POST['to'];

        $game = Game::createFromState($this->database, $_SESSION['game_state']);
        try {
            $game->play($piece, $to);
        } catch (InvalidMoveException $exception) {
            $_SESSION['error'] = $exception->getMessage();
        }
        $_SESSION['game_state'] = $game->getState();

        return new RedirectResponse('/');
    }

    public function move(): Response
    {
        session_start();

        /** @var string $from */
        $from = $_POST['from'];
        /** @var string $to */
        $to = $_POST['to'];

        unset($_SESSION['error']);

        $game = Game::createFromState($this->database, $_SESSION['game_state']);
        try {
            $game->move($from, $to);
        } catch (InvalidMoveException $exception) {
            $_SESSION['error'] = $exception->getMessage();
        }
        $_SESSION['game_state'] = $game->getState();

        return new RedirectResponse('/');
    }

    public function pass(): Response
    {
        session_start();

        $game = Game::createFromState($this->database, $_SESSION['game_state']);
        $game->pass();
        $_SESSION['game_state'] = $game->getState();

        return new RedirectResponse('/');
    }

    public function restart(): Response
    {
        session_start();

        $game = new Game($this->database);
        $_SESSION['game_state'] = $game->getState();

        return new RedirectResponse('/');
    }

    public function undo(): Response
    {
        session_start();

        $game = Game::createFromState($this->database, $_SESSION['game_state']);
        $game->undo();
        $_SESSION['game_state'] = $game->getState();

        return new RedirectResponse('/');
    }
}
