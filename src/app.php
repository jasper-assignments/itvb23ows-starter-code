<?php

namespace App;

$routes = [
    '' => ['App\\Controller\\DefaultController', 'index'],
    'play' => ['App\\Controller\\DefaultController', 'play'],
    'move' => ['App\\Controller\\DefaultController', 'move'],
    'pass' => ['App\\Controller\\DefaultController', 'pass'],
    'restart' => ['App\\Controller\\DefaultController', 'restart'],
    'undo' => ['App\\Controller\\DefaultController', 'undo'],
];

return $routes;
