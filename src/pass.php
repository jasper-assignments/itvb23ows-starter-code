<?php

session_start();

$db = include_once 'database.php';
$stmt = $db->prepare('
    INSERT INTO moves (game_id, type, move_from, move_to, previous_id, state)
    VALUES (?, "pass", null, null, ?, ?)
');
$state = getState();
$stmt->bind_param('iis', $_SESSION['game_id'], $_SESSION['last_move'], $state);
$stmt->execute();
$_SESSION['last_move'] = $db->insert_id;
$_SESSION['player'] = 1 - $_SESSION['player'];

header('Location: index.php');
