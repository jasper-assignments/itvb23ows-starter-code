<?php

namespace App\Controller;

use App\Entity\Ai;
use App\Entity\Database;
use App\Entity\Game;
use App\Exception\InvalidMoveException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    private Database $database;
    private Ai $ai;

    public function __construct(Database $database, Ai $ai)
    {
        $this->database = $database;
        $this->ai = $ai;
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

        $game = Game::createFromState($this->database, $this->ai, $_SESSION['game_state']);
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

        $game = Game::createFromState($this->database, $this->ai, $_SESSION['game_state']);
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

        $game = Game::createFromState($this->database, $this->ai, $_SESSION['game_state']);
        try {
            $game->pass();
        } catch (InvalidMoveException $exception) {
            $_SESSION['error'] = $exception->getMessage();
        }
        $_SESSION['game_state'] = $game->getState();

        return new RedirectResponse('/');
    }

    public function restart(): Response
    {
        session_start();

        $game = new Game($this->database, $this->ai);
        $_SESSION['game_state'] = $game->getState();

        return new RedirectResponse('/');
    }

    public function undo(): Response
    {
        session_start();

        $game = Game::createFromState($this->database, $this->ai, $_SESSION['game_state']);
        try {
            $game->undo();
        } catch (InvalidMoveException $exception) {
            $_SESSION['error'] = $exception->getMessage();
        }
        $_SESSION['game_state'] = $game->getState();

        return new RedirectResponse('/');
    }

    public function ai(): Response
    {
        session_start();

        $game = Game::createFromState($this->database, $this->ai, $_SESSION['game_state']);
        $game->makeAiMove();
        $_SESSION['game_state'] = $game->getState();

        return new RedirectResponse('/');
    }
}
