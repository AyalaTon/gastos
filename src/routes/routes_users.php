<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

require_once '../src/controllers/ctr_user.php';

return function (App $app){
	$container = $app->getContainer();
	$userController = new ctr_user();

}
?>