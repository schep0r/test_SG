<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use App\Application;

require_once __DIR__ . '/../vendor/autoload.php';
$routes = require_once '../routs.php';

$request = Request::createFromGlobals();
$response = new Response();

$app = new Application();

/** @var Route $route */
foreach ($routes as $route) {
    $app->map($route->getPath(), $route);
}

$response = $app->handle($request);
$response->send();
