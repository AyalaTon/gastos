<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

require_once '../src/controllers/ctr_user.php';

return function (App $app) {
    $userController = new ctr_user();
    $container = $app->getContainer();
    $routesU = require_once __DIR__ . "/../src/routes/routes_users.php";
    $routesA = require_once __DIR__ . "/../src/routes/routes_api.php";
    $routesU($app);
    $routesA($app);

    $app->get('/', function ($request, $response, $args) use ($container, $userController) {
        $args['version'] = '?'.LASTUPDATE;
        if (isset($_SESSION['userSession'])) {
            $responseValidateSession = $userController->validateSession();
            if($responseValidateSession->result == 2){
                // ACA LAS COSAS DE LOS LOGEADOS
                $args['userSession'] = $responseValidateSession->session;
            } else {
                return $response->withStatus(302)->withHeader('Location', 'ingresar');
            }
            return $this->view->render($response, "index.twig", $args);
        } else {
            return $response->withStatus(302)->withHeader('Location', 'ingresar');
        }
    })->setName("Home");
};
