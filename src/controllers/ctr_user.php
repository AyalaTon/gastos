<?php

require_once '../src/class/defaultclass/user.php';

class ctr_user{
	/* INICIAR SESIÓN: En caso del usuario no tener contraseña le asigna la ingresada y se guarda un objeto en sesion (usuario, token)*/
	public function signIn($user, $password){
		$historyClass = new history();
		$userClass = new user();
		$response = new \stdClass();

		$responseGetUser = $userClass->getUserByEmail($user); // MODIFIED
		// return json_encode($responseGetUser->message);
		if($responseGetUser->result == 2){
			if(is_null($responseGetUser->objectResult->pass)){ // Si la Respuesta de GetUserByEmail devuelve una Pass vacia, seteo la que envie
				$responseUpdatePassword = $userClass->updateUserPassword($responseGetUser->objectResult->id, $password);
				if($responseUpdatePassword->result == 2){
					$responseLogin = $historyClass->newLogin($responseGetUser->objectResult->id); // Guardo en el historial el login y no me interesa la respuesta
					return $userClass->setNewTokenAndSession($responseGetUser->objectResult->id);
				}else return $responseUpdatePassword;
			} else {
				if(strcmp($password, $responseGetUser->objectResult->pass) == 0){
					$responseLogin = $historyClass->newLogin($responseGetUser->objectResult->id); // Guardo en el historial el login y no me interesa la respuesta
					return $userClass->setNewTokenAndSession($responseGetUser->objectResult->id);
				} else {
					$response->result = 0;
					$response->message = "Usuario y contraseña no coinciden por favor vuelva a ingresarlos.";
				}
			}
		}else return $responseGetUser;

		return $response;
	}

	public function validateCurrentSession(){
		$userClass = new user();
		$response = new \stdClass();

		if(isset($_SESSION['userSession'])){
			$currentSession = $_SESSION['userSession'];
			$responseGetUser = $userClass->getUserById($currentSession->idUser);
			if($responseGetUser->result == 2){
				if(strcmp($currentSession->token, $responseGetUser->objectResult->token) == 0){
					$response->result = 2;
					$response->currentSession = $currentSession;
				}else{
					$response->result = 0;
					$response->message = "La sesión del usuario caducó por favor vuelva a ingresar.";
				}
			}else{
				$response->result = 0;
				$response->message = "La sesión detectada no es valida, por favor vuelva a ingresar.";
			}
		}else{
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
        }else return $responseGetUserInfo;
        return $response;
	}

	public function updateUserMail($idUser, $email){
		$userClass = new user();
		$response = new \stdClass();
		
		$responseUpdateMail = $userClass->updateUserMail($idUser, $email);
		if($responseUpdateMail->result == 2){ // Ahora deberia crear el txt
            $response->result = 2;
            $response->message = "Email actualizado con éxito!";
        }else return $responseUpdateMail;
        return $response;
	}
}