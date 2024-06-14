<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

require_once '../src/controllers/ctr_user.php';

return function (App $app){
	$container = $app->getContainer();
	$userController = new ctr_user();

	$app->get('/ingresar', function ($request, $response, $args) use ($container, $userController){
		$responseValidateSession = $userController->validateSession();
		if($responseValidateSession->result != 2){
			$args['version'] = '?'.LASTUPDATE;
			return $this->view->render($response, "signIn.twig", $args);
		}
		return $response->withRedirect($request->getUri()->getBaseUrl());
	})->setName("SignIn");
	
	$app->get('/salir', function ($request, $response, $args) use ($container, $userController){
		$responseValidateSession = $userController->validateSession();
		if($responseValidateSession->result == 2){
			$responseLogout = $userController->signOut();
		}
		return $response->withRedirect($request->getUri()->getBaseUrl());
	})->setName("SignOut");

	$app->post('/signIn', function(Request $request, Response $response) use ($userController){
		$data = $request->getParams();
		$user = $data['user'];
		$password = $data['password'];
		return json_encode($userController->signIn($user, sha1($password)));
	});
	
	$app->get('/test', function ($request, $response, $args) use ($container) {
        $args['version'] = '?'.LASTUPDATE;
		return $this->view->render($response, "test.twig", $args);
	})->setName("Test");

	$app->get('/list', function ($request, $response, $args) use ($container, $userController) {
		$responseFunction = $userController->getListUsers();
		$listUsers = $responseFunction->users;
		$args['users'] = $listUsers;
        $args['version'] = '?'.LASTUPDATE;
		return $this->view->render($response, "list.twig", $args);
	})->setName("List");
}


?>