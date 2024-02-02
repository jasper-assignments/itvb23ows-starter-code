<?php

    use App\Entity\Database;
    use App\Entity\Game;

    session_start();

    if (!isset($_SESSION['game_state'])) {
        header('Location: restart');
        exit(0);
    }

    $game = Game::createFromState(new Database(), $_SESSION['game_state']);

    $board = $game->getBoard();
    $hands = $game->getHands();
    $currentPlayer = $game->getCurrentPlayer();
    $winner = $game->getWinner();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Hive</title>
        <style>
            div.board {
                width: 60%;
                height: 100%;
                min-height: 500px;
                float: left;
                overflow: scroll;
                position: relative;
            }

            div.board div.tile {
                position: absolute;
            }

            div.tile {
                display: inline-block;
                width: 4em;
                height: 4em;
                border: 1px solid black;
                box-sizing: border-box;
                font-size: 50%;
                padding: 2px;
            }

            div.tile span {
                display: block;
                width: 100%;
                text-align: center;
                font-size: 200%;
            }

            div.player0 {
                color: black;
                background: white;
            }

            div.player1 {
                color: white;
                background: black
            }

            div.stacked {
                border-width: 3px;
                border-color: red;
                padding: 0;
            }
        </style>
    </head>
    <body>
        <div class="board">
            <?php
                $min_p = 1000;
                $min_q = 1000;
                foreach ($board->getTiles() as $pos => $tile) {
                    $pq = explode(',', $pos);
                    if ($pq[0] < $min_p) {
                        $min_p = $pq[0];
                    }
                    if ($pq[1] < $min_q) {
                        $min_q = $pq[1];
                    }
                }
                foreach (array_filter($board->getTiles()) as $pos => $tile) {
                    $pq = explode(',', $pos);
                    $pq[0];
                    $pq[1];
                    $h = count($tile);
                    echo '<div class="tile player';
                    echo $tile[$h-1][0];
                    if ($h > 1) {
                        echo ' stacked';
                    }
                    echo '" style="left: ';
                    echo ($pq[0] - $min_p) * 4 + ($pq[1] - $min_q) * 2;
                    echo 'em; top: ';
                    echo ($pq[1] - $min_q) * 4;
                    echo "em;\">($pq[0],$pq[1])<span>";
                    echo $tile[$h-1][1];
                    echo '</span></div>';
                }
            ?>
        </div>
        <div class="hand">
            White:
            <?php
                foreach ($hands[0]->getPieces() as $tile => $ct) {
                    for ($i = 0; $i < $ct; $i++) {
                        echo '<div class="tile player0"><span>'.$tile.'</span></div> ';
                    }
                }
            ?>
        </div>
        <div class="hand">
            Black:
            <?php
            foreach ($hands[1]->getPieces() as $tile => $ct) {
                for ($i = 0; $i < $ct; $i++) {
                    echo '<div class="tile player1"><span>'.$tile."</span></div> ";
                }
            }
            ?>
        </div>
        <div class="turn">
            Turn: <?php
                if ($currentPlayer == 0) {
                    echo "White";
                } else {
                    echo "Black";
                }
            ?>
        </div>
        <?php if ($winner !== null): ?>
            <strong><?php
                if ($winner === -1) {
                    echo 'It\'s a tie!';
                } else {
                    $winnerLabel = 'White';
                    if ($winner === 1) {
                        $winnerLabel = 'Black';
                    }
                    echo "$winnerLabel won!";
                }
            ?></strong>
        <?php else: ?>
            <form method="post" action="/play">
                <select name="piece">
                    <?php
                        foreach ($hands[$currentPlayer]->getAvailablePieces() as $tile => $ct) {
                            echo "<option value=\"$tile\">$tile</option>";
                        }
                    ?>
                </select>
                <select name="to">
                    <?php
                        foreach ($game->getValidPlayPositions() as $pos) {
                            echo "<option value=\"$pos\">$pos</option>";
                        }
                    ?>
                </select>
                <input type="submit" value="Play">
            </form>
            <form method="post" action="/move">
                <select name="from">
                    <?php
                        foreach ($board->getAllPositionsOwnedByPlayer($currentPlayer) as $pos) {
                            echo "<option value=\"$pos\">$pos</option>";
                        }
                    ?>
                </select>
                <select name="to">
                    <?php
                        foreach ($game->getToPositions() as $pos) {
                            echo "<option value=\"$pos\">$pos</option>";
                        }
                    ?>
                </select>
                <input type="submit" value="Move">
            </form>
            <form method="post" action="/pass">
                <input type="submit" value="Pass">
            </form>
        <?php endif; ?>
        <form method="post" action="/restart">
            <input type="submit" value="Restart">
        </form>
        <strong><?php
            if (isset($_SESSION['error'])) {
                echo $_SESSION['error'];
            }
            unset($_SESSION['error']);
        ?></strong>
        <ol>
            <?php
                $moves = $game->getAllMoves();
                foreach ($moves as $row) {
                    echo '<li>'.$row[2].' '.$row[3].' '.$row[4].'</li>';
                }
            ?>
        </ol>
        <form method="post" action="/undo">
            <input type="submit" value="Undo">
        </form>
    </body>
</html>
