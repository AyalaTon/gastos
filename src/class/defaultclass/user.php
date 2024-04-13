<?php

require_once '../src/connection/open_connection.php';

class user{
	
	public function updateUserPassword($idUser, $newPassword){
		$dbClass = new DataBase();
		return $dbClass->sendQuery("UPDATE usuario SET pass = ? WHERE id = ?", array('si', $newPassword, $idUser), "BOOLE");
	}

	public function getListUsers(){
		$dbClass = new DataBase();
		return $dbClass->sendQuery("SELECT * FROM usuario", null, "LIST");
	}

	public function setNewTokenAndSession($idUser){
		$dbClass = new DataBase();
		$newToken = bin2hex(random_bytes((100 - (100 % 2)) / 2));
		$responseQuery = $dbClass->sendQuery('UPDATE usuario SET token = ? WHERE id = ?', array('si', $newToken, $idUser), "BOOLE");
		if($responseQuery->result == 2){
			$responseQuery = null;
			$responseQuery = $this->getUserById($idUser);
			if($responseQuery->result == 2){
				$objectSession = new \stdClass();
				$objectSession->idUser = $responseQuery->objectResult->id;
				$objectSession->nick = $responseQuery->objectResult->user;
				$objectSession->token = $responseQuery->objectResult->token;
				$_SESSION['userSession'] = $objectSession;
				unset($responseQuery->objectResult);
			}
		} else $responseQuery->message = "Un error interno no permitio iniciar sesión con este usuario.";
		return $responseQuery;
	}

	public function validateCurrentSession(){
		$userClass = new users();
		$response = new \stdClass();

		if(isset($_SESSION['userSession'])){
			$currentSession = $_SESSION['userSession'];
			$responseGetUser = $userClass->getUserById($currentSession->idUser);
			if($responseGetUser->result == 2){
				if(strcmp($currentSession->token, $responseGetUser->objectResult->token) == 0){
					$response->result = 2;
					$response->currentSession = $currentSession;
				} else {
					$response->result = 0;
					$response->message = "La sesión del caducó, por favor vuelva a ingresar.";
				}
			} else {
				$response->result = 0;
				$response->message = "La sesión no es valida, por favor vuelva a ingresar.";
			}
		} else {
			$response->result = 0;
			$response->message = "Actulamente no hay una sesión activa en el sistema.";
		}
		return $response;
	}

	public function getUserById($idUser){
		$dbClass = new DataBase();
		$responseQuery = $dbClass->sendQuery("SELECT * FROM usuario WHERE id = ?", array('i', $idUser), "OBJECT");
		// var_dump($responseQuery);
		if($responseQuery->result == 1)
			$responseQuery->message = "El identificador ingresado no corresponde a un usuario registrado.";
		return $responseQuery;
	}
	public function getUserByUser($user){
		$dbClass = new DataBase();
		$responseQuery = $dbClass->sendQuery("SELECT * FROM usuario WHERE usuario.user = ?", array('s', $user), "OBJECT");
		// var_dump($responseQuery);
		if($responseQuery->result == 1)
			$responseQuery->message = "El identificador ingresado no corresponde a un usuario registrado.";
		return $responseQuery;
	}
}