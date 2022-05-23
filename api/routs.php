<?php

use App\Controller\ConvertController;
use App\Controller\PrizeController;
use Symfony\Component\Routing\Route;

const CONTROLLER_KEY = "_controller";

return [
    new Route('/auth', [CONTROLLER_KEY => "App\Controller\LoginController::auth"], [], [], '', [], ["POST"]),
    new Route('/prize', [CONTROLLER_KEY => "App\Controller\PrizeController::getPrize"], [], [], '', [], ["POST"]),
    new Route('/prize/decline', [CONTROLLER_KEY => "App\Controller\PrizeController::declinePrize"], [], [], '', [], ["POST"]),
    new Route('/convert/money', [CONTROLLER_KEY => "App\Controller\ConvertController::update"], [], [], '', [], ["POST"]),
];