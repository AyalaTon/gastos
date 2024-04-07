<?php

use Slim\App;
use Slim\Http\Response;

require_once '../src/controllers/ctr_user.php';

return function (App $app) {
    $routesU = require_once __DIR__ . "/../src/routes/routes_users.php";
    $routesA = require_once __DIR__ . "/../src/routes/routes_api.php";
    $container = $app->getContainer();
    $routesU($app);
    $routesA($app);

    $app->get('/', function ($request, $response, $args) use ($container) {
        return $this->view->render($response, "index.twig", $args);
    })->setName("Home");
};
