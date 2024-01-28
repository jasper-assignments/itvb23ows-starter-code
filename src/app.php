<?php

use App\Controller\DefaultController;

$routes = [
    '' => [DefaultController::class, 'index'],
    'play' => [DefaultController::class, 'play'],
    'move' => [DefaultController::class, 'move'],
    'pass' => [DefaultController::class, 'pass'],
    'restart' => [DefaultController::class, 'restart'],
    'undo' => [DefaultController::class, 'undo'],
];

return $routes;
