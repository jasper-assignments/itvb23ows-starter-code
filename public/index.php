<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Entity\Ai;
use App\Entity\Database;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;

function render_template(string $name): Response
{
    ob_start();
    include sprintf(__DIR__ . '/../src/pages/%s.php', $name);

    return new Response(ob_get_clean());
}

$url = $_SERVER['REQUEST_URI'];
$parts = parse_url($url);
$path = isset($parts['path']) ? trim($parts['path'], '/') : '';

$routes = require __DIR__ . '/../src/app.php';

if (!isset($routes[$path])) {
    $response = new Response('Not Found', 404);
} else {
    try {
        [$controller, $method] = $routes[$path];
        $database = new Database();
        $ai = new Ai(new Client(['base_uri' => $_ENV['AI_BASE_URI']]));
        $response = call_user_func([new $controller(database: $database, ai: $ai), $method]);
    } catch (Exception $exception) {
        $response = new Response('An error occurred', 500);
    }
}

$response->send();
