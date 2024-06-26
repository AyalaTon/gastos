<?php

require_once '../src/class/defaultclass/user.php';

class ctr_user{
	/* INICIAR SESIÓN: En caso del usuario no tener contraseña le asigna la ingresada y se guarda un objeto en sesion (usuario, token)*/
	public function signIn($user, $password){
		$userClass = new user();
		$response = new \stdClass();
		$responseGetUser = $userClass->getUserByUser($user); // MODIFIED
		if($responseGetUser->result == 2){
			if(is_null($responseGetUser->objectResult->pass)){ // Si la Respuesta de GetUserByEmail devuelve una Pass vacia, seteo la que envie
				$responseUpdatePassword = $userClass->updateUserPassword($responseGetUser->objectResult->id, $password);
				if($responseUpdatePassword->result == 2){
					return $userClass->setNewTokenAndSession($responseGetUser->objectResult->id);
				} else return $responseUpdatePassword;
			} else {
				if(strcmp($password, $responseGetUser->objectResult->pass) == 0){
					return $userClass->setNewTokenAndSession($responseGetUser->objectResult->id);
				} else {
					$response->result = 1;
					$response->message = "Usuario y contraseña no coinciden por favor vuelva a ingresarlos.";
				}
			}
		} else return $responseGetUser;

		return $response;
	}

	public function signOut(){
		$usersClass = new user();
		$response = new \stdClass();
		$response->result = 1;
		if(isset($_SESSION['userSession'])){
			$currentSession = $_SESSION['userSession'];
			$response = $usersClass->updateToken($currentSession->idUser, null);
			if($response->result == 2)
				session_destroy();
		}
		return $response;
	}

	public function validateSession(){
		$userClass = new user();
		$response = new \stdClass();

		if(isset($_SESSION['userSession'])){
			$currentSession = $_SESSION['userSession'];
			$responseGetUser = $userClass->getUserById($currentSession->idUser);
			if($responseGetUser->result == 2){
				if(strcmp($currentSession->token, $responseGetUser->objectResult->token) == 0){
					$response->result = 2;
					$response->session = $currentSession;
				} else {
					$response->result = 0;
					$response->message = "La sesión del usuario caducó por favor vuelva a ingresar.";
				}
			} else {
				$response->result = 0;
				$response->message = "La sesión detectada no es valida, por favor vuelva a ingresar.";
			}
		} else {
			$response->result = 0;
			$response->message = "Actulamente no hay una sesión activa en el sistema.";
		}
		return $response;
	}

	public function getUserData($idUser){
		$userClass = new user();
		$response = new \stdClass();
		
		$responseGetUserInfo = $userClass->getUserById($idUser);
		if($responseGetUserInfo->result == 2){
			$response->result = 2;
            $response->usuario = $responseGetUserInfo->objectResult;
            $response->usuario->pass = "";
            $response->usuario->token = "";
        } else return $responseGetUserInfo;
        return $response;
	}
	
	public function getListUsers(){
		$userClass = new user();
		$response = new \stdClass();
		
		$responseGetUsers = $userClass->getListUsers();
		if($responseGetUsers->result == 2){
			$response->result = 2;
			$response->users = $responseGetUsers->listResult;
		} else return $responseGetUsers;
		return $response;
	}
}