<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

require_once '../src/controllers/ctr_user.php';

return function (App $app){
	$container = $app->getContainer();
	$userController = new ctr_user();

	$app->post('/signIn', function(Request $request, Response $response) use ($container, $userController){
		// $responseCurrentSession = $userController->validateCurrentSession();
		// if($responseCurrentSession->result != 2){ // No hay sesion activa
			$data = $request->getParams();
			$user = $data['user'];
			$password = $data['password'];
			return json_encode($userController->signIn($user, sha1($password)));
		// }else return json_encode($responseCurrentSession);
	});
}
?>